<?php
session_start();
require_once 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$usuarioId = $_SESSION['usuarioId'];

class EstoqueController {

    public function listarMateriais() {
        try {
            $conn = getConnection();
    
            $stmt = $conn->query("SELECT * FROM estoque");
            $materiais = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Adicione headers para evitar saída extra
            header('Content-Type: application/json');
            echo json_encode($materiais);
            exit; // Certifique-se de interromper a execução aqui
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            exit; // Impede qualquer saída extra
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
            header("Location: http://localhost/estoque-php/front/home.php?status=success&message=" . urlencode("Material cadastrado com sucesso"));
            exit;

        } catch (PDOException $e) {
            // Redireciona para home.php com mensagem de erro
            header("Location:  http://localhost/estoque-php/front/home.php?status=error&message=" . urlencode($e->getMessage()));
            exit;
        }
    }

    public function buscarMateriais() {
        $conn = getConnection();
        if (!$conn) {
            die("Erro ao conectar ao banco de dados");
        }
    
        // Inclua 'preco' na consulta SQL
        $sql = "SELECT id, descricao, unidade_medida, quantidade, deposito, estoque_minimo, estoque_seguranca, tipo_material, segmento, preco FROM estoque";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function buscarMaterialPorId($id) {
        $conn = getConnection();
        if (!$conn) {
            die("Erro ao conectar ao banco de dados");
        }
    
        $sql = "SELECT * FROM estoque WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarMaterial($id, $descricao, $unidade_medida, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material, $segmento) {
        $conn = getConnection();
        if (!$conn) {
            die("Erro ao conectar ao banco de dados");
        }
    
        $sql = "UPDATE estoque SET descricao = ?, unidade_medida = ?, quantidade = ?, deposito = ?, estoque_minimo = ?, estoque_seguranca = ?, tipo_material = ?, segmento = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$descricao, $unidade_medida, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material, $segmento, $id]);
    }
    
    public function emitirNotaFiscal($produtos, $usuarioId) {
        $conn = getConnection();
        try {
            $conn->beginTransaction();
    
            $totalNota = 0;
            $precos = []; // Cache dos preços
            foreach ($produtos as $produto) {
                $produtoId = $produto['produto_id'];
                $quantidade = $produto['quantidade'];
    
                if (!isset($precos[$produtoId])) {
                    $stmt = $conn->prepare("SELECT preco FROM estoque WHERE id = ?");
                    $stmt->execute([$produtoId]);
                    $produtoData = $stmt->fetch(PDO::FETCH_ASSOC);
    
                    if (!$produtoData) {
                        throw new Exception('Produto não encontrado');
                    }
    
                    $precos[$produtoId] = $produtoData['preco'];
                }
    
                $subtotal = $quantidade * $precos[$produtoId];
                $totalNota += $subtotal;
            }
    
            $stmt = $conn->prepare("INSERT INTO notas (usuario_id, data_venda, hora_venda, total_venda) VALUES (?, CURDATE(), CURTIME(), ?)");
            $stmt->execute([$usuarioId, $totalNota]);
            $notaId = $conn->lastInsertId();
    
            foreach ($produtos as $produto) {
                $produtoId = $produto['produto_id'];
                $quantidade = $produto['quantidade'];
                $preco = $precos[$produtoId];
                $subtotal = $quantidade * $preco;
    
                $stmtItem = $conn->prepare("INSERT INTO nota_itens (nota_id, produto_id, quantidade, preco_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
                $stmtItem->execute([$notaId, $produtoId, $quantidade, $preco, $subtotal]);
    
                $stmtEstoque = $conn->prepare("UPDATE estoque SET quantidade = quantidade - ? WHERE id = ?");
                $stmtEstoque->execute([$quantidade, $produtoId]);
            }
    
            $conn->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $conn->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function verificarEstoque($produtoId, $quantidadeSolicitada) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT quantidade FROM estoque WHERE id = ?");
        $stmt->execute([$produtoId]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($produto && $produto['quantidade'] >= $quantidadeSolicitada) {
            return true; // Quantidade suficiente disponível
        } else {
            return false; // Quantidade insuficiente
        }
    }

    public function listarTiposMaterial() {
        try {
            $conn = getConnection();
            $sql = "SELECT DISTINCT tipo_material FROM estoque ORDER BY tipo_material ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $tiposMaterial = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $tiposMaterial;
        } catch (PDOException $e) {
            http_response_code(500);
            return ['error' => $e->getMessage()];
        }
    }

    public function listarSegmentos() {
        try {
            $conn = getConnection();
            $sql = "SELECT DISTINCT segmento FROM estoque ORDER BY segmento ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $segmentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $segmentos;
        } catch (PDOException $e) {
            http_response_code(500);
            return ['error' => $e->getMessage()];
        }
    }
}
    
// Verifica se o formulário foi enviado
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

// Processamento do formulário de emissão de nota
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'emitirNota') {
    // Captura os dados enviados pelo formulário
    $produtos = json_decode($_POST['itens'], true);

    $estoqueController = new EstoqueController();
    $result = $estoqueController->emitirNotaFiscal($produtos, $usuarioId);
    
    // Redireciona de volta para emissao_notas.php com a mensagem de status
    if ($result['success']) {
        header('Location: ../front/emissao_notas.php?status=success&message=' . urlencode('Nota emitida com sucesso'));
    } else {
        header('Location: ../front/emissao_notas.php?status=error&message=' . urlencode($result['message']));
    }
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'verificarEstoque') {
    $produtoId = $_GET['produto_id'];
    $quantidade = $_GET['quantidade'];

    $estoqueController = new EstoqueController();
    $disponivel = $estoqueController->verificarEstoque($produtoId, $quantidade);

    header('Content-Type: application/json');
    echo json_encode(['success' => $disponivel]);
    exit;
}



?>