<?php

require_once 'db.php';





class EstoqueController {

    public function listarTiposMaterial() {
        try {
            $conn = getConnection(); // Função para obter a conexão com o banco de dados
            $sql = "SELECT DISTINCT tipo_material FROM estoque ORDER BY tipo_material ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna os resultados
        } catch (PDOException $e) {
            http_response_code(500);
            return ['error' => 'Erro ao listar tipos de material: ' . $e->getMessage()];
        }
    }

    public function listarSegmentos() {
        try {
            $conn = getConnection(); // Função para obter a conexão com o banco de dados
            $sql = "SELECT DISTINCT segmento FROM estoque ORDER BY segmento ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna os resultados
        } catch (PDOException $e) {
            http_response_code(500);
            return ['error' => 'Erro ao listar segmentos: ' . $e->getMessage()];
        }
    }

    public function listarMateriais() {
        try {
            $conn = getConnection();

            // Obter os parâmetros de filtro
            $descricao = isset($_GET['descricao']) ? $_GET['descricao'] : '';
            $tipo_material = isset($_GET['tipo_material']) && $_GET['tipo_material'] !== 'Todos' ? $_GET['tipo_material'] : '';
            $segmento = isset($_GET['segmento']) && $_GET['segmento'] !== 'Todos' ? $_GET['segmento'] : '';

            // Construir a consulta com filtros condicionais
            $sql = "SELECT * FROM estoque WHERE 1=1";
            $params = [];

            if (!empty($descricao)) {
                $sql .= " AND descricao LIKE ?";
                $params[] = "%" . $descricao . "%"; // Busca por correspondências parciais
            }

            if (!empty($tipo_material)) {
                $sql .= " AND tipo_material = ?";
                $params[] = $tipo_material;
            }

            if (!empty($segmento)) {
                $sql .= " AND segmento = ?";
                $params[] = $segmento;
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $materiais = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Retorna os dados em formato JSON
            header('Content-Type: application/json');
            echo json_encode($materiais);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


public function cadastrarMaterial($descricao, $unidade, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material, $segmento) {
        try {
            $conn = getConnection();
            $sql = "INSERT INTO estoque (descricao, unidade_medida, quantidade, deposito, estoque_minimo, estoque_seguranca, tipo_material, segmento) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$descricao, $unidade, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material, $segmento]);

            // Redireciona para home.php com mensagem de sucesso
            header("Location: http://localhost/almoxarifado/estoque-php/front/home.php?status=success");
            exit;
        } catch (PDOException $e) {
            // Redireciona para home.php com mensagem de erro
            header("Location: http://localhost/almoxarifado/estoque-php/front/home.php?status=error&message=" . urlencode($e->getMessage()));
            exit;
        }
    }
    

}


function verificarEstoque($produtoId) {
    $conn = getConnection();
    
    if (!$conn instanceof mysqli) {
        die("Erro na conexão com o banco de dados.");
    }

    $sql = "SELECT quantidade FROM estoque WHERE id = $produtoId";
    $result = $conn->query($sql);
    $quantidade = 0;

    if ($result && $row = $result->fetch_assoc()) {
        $quantidade = $row['quantidade'];
    }

    return $quantidade;
}



// Verifica se a ação é "listarMateriais" e chama a função
if (isset($_GET['action']) && $_GET['action'] === 'listarMateriais') {
    $controller = new EstoqueController();
    $controller->listarMateriais();
}
if ($_GET['action'] === 'excluirMaterial' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM estoque WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erro ao excluir o produto: " . $e->getMessage()]);
    }
    exit;
}
if (isset($_GET['action'])) {
    $controller = new EstoqueController();

    switch ($_GET['action']) {
        case 'listarMateriais':
            $controller->listarMateriais();
            break;

        case 'listarTiposMaterial':
            header('Content-Type: application/json');
            echo json_encode($controller->listarTiposMaterial());
            break;

        case 'listarSegmentos':
            header('Content-Type: application/json');
            echo json_encode($controller->listarSegmentos());
            break;

        case 'excluirMaterial':
            if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                $id = $_GET['id'];
                try {
                    $conn = getConnection();
                    $stmt = $conn->prepare("DELETE FROM estoque WHERE id = :id");
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    echo json_encode(["success" => true]);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["error" => "Erro ao excluir o produto: " . $e->getMessage()]);
                }
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(["error" => "Ação inválida"]);
            break;
    }
    exit;
}

if ($_GET['action'] === 'dadosGraficos') {
    $conn = getConnection();
    $query = "SELECT tipo_material, SUM(quantidade) as total FROM estoque GROUP BY tipo_material";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}




?>