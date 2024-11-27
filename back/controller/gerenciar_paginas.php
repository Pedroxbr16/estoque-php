<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$conn = getConnection();

switch ($action) {
    case 'listar':
        try {
            // Selecionar apenas os campos necessários (id e nome)
            $sql = "SELECT id, nome FROM paginas";
            $result = $conn->query($sql);

            $paginas = [];
            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $paginas[] = $row;
                }
            }
            echo json_encode($paginas);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Erro ao listar páginas: ' . $e->getMessage()]);
        }
        break;

    case 'buscar':
        $id = $_GET['id'] ?? '';
        try {
            $stmt = $conn->prepare("SELECT id, nome FROM paginas WHERE id = ?");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            $pagina = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($pagina);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar página: ' . $e->getMessage()]);
        }
        break;

    case 'criar':
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['pagina'])) {
            try {
                $stmt = $conn->prepare("INSERT INTO paginas (nome) VALUES (?)");
                $stmt->bindParam(1, $input['pagina'], PDO::PARAM_STR);
                $success = $stmt->execute();
                echo json_encode(['success' => $success]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => 'Erro ao criar página: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
        }
        break;

    case 'editar':
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['id'], $input['pagina'])) {
            try {
                $stmt = $conn->prepare("UPDATE paginas SET nome = ? WHERE id = ?");
                $stmt->bindParam(1, $input['pagina'], PDO::PARAM_STR);
                $stmt->bindParam(2, $input['id'], PDO::PARAM_INT);
                $success = $stmt->execute();
                echo json_encode(['success' => $success]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => 'Erro ao editar página: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
        }
        break;

    case 'excluir':
        $id = $_GET['id'] ?? '';
        try {
            $stmt = $conn->prepare("DELETE FROM paginas WHERE id = ?");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $success = $stmt->execute();
            echo json_encode(['success' => $success]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Erro ao excluir página: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Ação inválida']);
        break;
}

$conn = null;
?>
