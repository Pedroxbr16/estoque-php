<?php
session_start();
require_once 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$usuarioId = $_SESSION['usuarioId'];

class EstoqueController {
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
    
            // Calcula o total da nota fiscal
            $totalNota = 0;
            foreach ($produtos as $produto) {
                $produtoId = $produto['produto_id'];
                $quantidade = $produto['quantidade'];
                
                // Obtém o preço unitário do produto
                $stmt = $conn->prepare("SELECT preco FROM estoque WHERE id = ?");
                $stmt->execute([$produtoId]);
                $produtoData = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if (!$produtoData) {
                    throw new Exception('Produto não encontrado');
                }
    
                $preco = $produtoData['preco'];
                $subtotal = $quantidade * $preco;
                $totalNota += $subtotal;
            }
    
            // Insere a nota fiscal e obtém o ID
            $stmt = $conn->prepare("INSERT INTO notas (usuario_id, data_venda, hora_venda, total_venda) VALUES (?, CURDATE(), CURTIME(), ?)");
            $stmt->execute([$usuarioId, $totalNota]);
            $notaId = $conn->lastInsertId();
    
            // Insere cada item da nota na tabela nota_itens
            foreach ($produtos as $produto) {
                $produtoId = $produto['produto_id'];
                $quantidade = $produto['quantidade'];
                
                // Obtém o preço unitário do produto novamente
                $stmt = $conn->prepare("SELECT preco FROM estoque WHERE id = ?");
                $stmt->execute([$produtoId]);
                $produtoData = $stmt->fetch(PDO::FETCH_ASSOC);
                $preco = $produtoData['preco'];
                $subtotal = $quantidade * $preco;
    
                $stmtItem = $conn->prepare("INSERT INTO nota_itens (nota_id, produto_id, quantidade, preco_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
                $stmtItem->execute([$notaId, $produtoId, $quantidade, $preco, $subtotal]);
    
                // Atualiza o estoque do produto
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