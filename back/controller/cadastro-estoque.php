<?php
session_start();
require_once __DIR__ . '/../db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class EstoqueController {
    // Listar materiais do estoque
    public function listarMateriais($pagina = 1, $itensPorPagina = 10) {
        try {
            $conn = getConnection();
            $offset = ($pagina - 1) * $itensPorPagina;

            $sql = "SELECT * FROM estoque LIMIT :limit OFFSET :offset";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':limit', $itensPorPagina, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $materiais = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Contar total de itens para calcular o número de páginas
            $totalItens = $conn->query("SELECT COUNT(*) FROM estoque")->fetchColumn();
            $totalPaginas = ceil($totalItens / $itensPorPagina);

            // Retornar os dados e a paginação
            return [
                'materiais' => $materiais,
                'totalPaginas' => $totalPaginas,
                'paginaAtual' => $pagina
            ];
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    // Cadastrar novo material no estoque
    public function cadastrarMaterial($descricao, $unidade, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material, $segmento) {
        try {
            $conn = getConnection();
            $sql = "INSERT INTO estoque (descricao, unidade_medida, quantidade, deposito, estoque_minimo, estoque_seguranca, tipo_material, segmento) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$descricao, $unidade, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material, $segmento]);

            // Redireciona para a tela correta com base no usuário logado com SweetAlert
            $redirectUrl = isset($_SESSION['homeUrl']) ? $_SESSION['homeUrl'] : './front/home.php';
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: 'Material cadastrado com sucesso!'
                        }).then(() => {
                            window.location.href = '$redirectUrl';
                        });
                    });
                  </script>";
            exit;
        } catch (PDOException $e) {
            // Redireciona para a tela correta com base no usuário logado com SweetAlert
            $redirectUrl = isset($_SESSION['homeUrl']) ? $_SESSION['homeUrl'] : './front/home.php';
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Erro ao cadastrar o material: {$e->getMessage()}'
                        }).then(() => {
                            window.location.href = '$redirectUrl';
                        });
                    });
                  </script>";
            exit;
        }
    }

    // Listar categorias do estoque, como unidade de medida, tipo de material, etc.
    public function listarCategorias($categoria) {
        try {
            $conn = getConnection();
            $sql = "SELECT descricao FROM $categoria ORDER BY descricao ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $categorias;
        } catch (PDOException $e) {
            http_response_code(500);
            return ['error' => $e->getMessage()];
        }
    }
}

// Verifica se o formulário foi enviado para cadastrar um material
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'cadastrar') {
    $descricao = $_POST['descricao'];
    $unidade = $_POST['unidade_medida'];
    $quantidade = $_POST['quantidade'];
    $deposito = $_POST['deposito'];
    $estoque_minimo = $_POST['estoque_minimo'];
    $estoque_seguranca = $_POST['estoque_seguranca'];
    $tipo_material = $_POST['tipo_material'];
    $segmento = $_POST['segmento'];
    
    $estoqueController = new EstoqueController();
    $estoqueController->cadastrarMaterial($descricao, $unidade, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material, $segmento);
}

// Verifica se a ação foi passada via GET para listar materiais ou categorias
if (isset($_GET['action'])) {
    $controller = new EstoqueController();

    switch ($_GET['action']) {
        case 'listarMateriais':
            $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $itensPorPagina = isset($_GET['itensPorPagina']) ? (int)$_GET['itensPorPagina'] : 10;

            header('Content-Type: application/json');
            echo json_encode($controller->listarMateriais($pagina, $itensPorPagina));
            break;

        case 'listarCategorias':
            if (isset($_GET['categoria'])) {
                $categoria = $_GET['categoria'];
                header('Content-Type: application/json');
                echo json_encode($controller->listarCategorias($categoria));
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Categoria não especificada']);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Ação inválida']);
            break;
    }
    exit; // Finaliza o processamento após tratar a ação
}
?>
