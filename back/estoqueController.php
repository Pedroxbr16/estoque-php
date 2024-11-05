<?php
include('db.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class EstoqueController {
    public function cadastrarMaterial($descricao, $unidade, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material, $segmento ) {
        try {
            $conn = getConnection();
            $sql = "INSERT INTO estoque (descricao, unidade_medida, quantidade, deposito, estoque_minimo, estoque_seguranca, tipo_material, segmento ) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$descricao, $unidade, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material, $segmento ]);

            // Redireciona para home.php com mensagem de sucesso
            header("Location: http://localhost/almoxarifado/estoque-php/front/home.php?status=success");
            exit;

        } catch (PDOException $e) {
            // Redireciona para home.php com mensagem de erro
            header("Location:  http://localhost/almoxarifado/estoque-php/front/home.php?status=error&message=" . urlencode($e->getMessage()));
            exit;
        }
    }

    public function buscarMateriais() {
        $conn = getConnection();
        if (!$conn) {
            die("Erro ao conectar ao banco de dados");
        }

        $sql = "SELECT id, descricao, unidade_medida, quantidade, deposito, estoque_minimo, estoque_seguranca, tipo_material, segmento FROM estoque";
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
    $segmento =$_POST['segmento'];
    $estoqueController = new EstoqueController();
    $estoqueController->cadastrarMaterial($descricao, $unidade, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material, $segmento);
}
?>
