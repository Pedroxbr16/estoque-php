<?php
include('db.php');

class EstoqueController {
    public function cadastrarMaterial($descricao, $unidade, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material) {
        $conn = getConnection();
        $sql = "INSERT INTO estoque (descricao, unidade_medida, quantidade, deposito, estoque_minimo, estoque_seguranca, tipo_material) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$descricao, $unidade, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material]);
    }

    public function alterarMaterial($id, $descricao, $unidade, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material) {
        $conn = getConnection();
        $sql = "UPDATE estoque SET descricao=?, unidade_medida=?, quantidade=?, deposito=?, estoque_minimo=?, estoque_seguranca=?, tipo_material=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$descricao, $unidade, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material, $id]);
    }

    public function excluirMaterial($id) {
        $conn = getConnection();
        $sql = "DELETE FROM estoque WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
    }

    public function listarMateriaisPorDeposito($deposito) {
        $conn = getConnection();
        $sql = "SELECT * FROM estoque WHERE deposito=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$deposito]);
        return $stmt->fetchAll();
    }

    public function alertaEstoque($id) {
        $conn = getConnection();
        $sql = "SELECT quantidade, estoque_minimo, estoque_seguranca FROM estoque WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $material = $stmt->fetch();
        
        if ($material['quantidade'] <= $material['estoque_minimo']) {
            return "Alerta: Estoque abaixo do mínimo!";
        } elseif ($material['quantidade'] <= $material['estoque_seguranca']) {
            return "Alerta: Estoque em nível de segurança!";
        }
    }
}
?>
