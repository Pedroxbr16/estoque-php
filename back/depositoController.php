<?php
include('db.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class EstoqueController {
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


// Verifica se a ação é "listarMateriais" e chama a função
if (isset($_GET['action']) && $_GET['action'] === 'listarMateriais') {
    $controller = new EstoqueController();
    $controller->listarMateriais();
}
?>