<?php
session_start();
require_once '../db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class EstoqueEdit {

    public function listarCategorias($categoria) {
        $conn = getConnection();
        if ($conn) {
            try {
                // Mapear as categorias para suas respectivas tabelas
                $tableMap = [
                    'unidade_medida' => 'unidade_medida',
                    'deposito' => 'deposito',
                    'segmento' => 'segmento',
                    'tipo_material' => 'tipo_material'
                ];
    
                if (!array_key_exists($categoria, $tableMap)) {
                    throw new Exception("Categoria inválida: $categoria");
                }
    
                $tabela = $tableMap[$categoria];
    
                // Consultar todos os valores da categoria
                $sql = "SELECT id, descricao FROM $tabela";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
    
                $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
                header('Content-Type: application/json');
                echo json_encode(["status" => "success", "data" => $categorias]);
                exit(); // Use exit() para garantir que a execução pare aqui
            } catch (PDOException $e) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(["status" => "error", "message" => "Erro ao listar categorias de $categoria: " . $e->getMessage()]);
                error_log("Erro ao listar categorias: " . $e->getMessage());
                exit();
            }
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => "Erro ao conectar ao banco de dados."]);
            exit();
        }
    }

    public function editarItem($id, $descricaoNova, $categoria) {
        $conn = getConnection();
        if ($conn) {
            try {
                // Mapear as categorias para suas respectivas tabelas
                $tableMap = [
                    'unidade_medida' => 'unidade_medida',
                    'deposito' => 'deposito',
                    'segmento' => 'segmento',
                    'tipo_material' => 'tipo_material'
                ];
    
                // Verificar se a categoria é válida
                if (!array_key_exists($categoria, $tableMap)) {
                    throw new Exception("Categoria inválida: $categoria");
                }
    
                $tabela = $tableMap[$categoria];
    
                // Atualizar o valor na tabela correta
                $sql = "UPDATE $tabela SET descricao = :descricaoNova WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':descricaoNova', $descricaoNova);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
    
                header('Content-Type: application/json');
                echo json_encode(["status" => "success", "message" => "Item atualizado com sucesso"]);
                exit();
            } catch (PDOException $e) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(["status" => "error", "message" => "Erro ao editar item em $categoria: " . $e->getMessage()]);
                exit();
            }
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => "Erro ao conectar ao banco de dados."]);
            exit();
        }
    }
    
    public function excluirItem($id, $categoria) {
        $conn = getConnection();
        if ($conn) {
            try {
                // Mapear as categorias para suas respectivas tabelas
                $tableMap = [
                    'unidade_medida' => 'unidade_medida',
                    'deposito' => 'deposito',
                    'segmento' => 'segmento',
                    'tipo_material' => 'tipo_material'
                ];
    
                // Verificar se a categoria é válida
                if (!array_key_exists($categoria, $tableMap)) {
                    throw new Exception("Categoria inválida: $categoria");
                }
    
                $tabela = $tableMap[$categoria];
    
                // Executar a exclusão do item na tabela correta
                $sql = "DELETE FROM $tabela WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
    
                // Responder com sucesso
                header('Content-Type: application/json');
                echo json_encode(["status" => "success", "message" => "Item excluído com sucesso"]);
                exit();
            } catch (PDOException $e) {
                // Em caso de erro na exclusão, enviar resposta com erro
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(["status" => "error", "message" => "Erro ao excluir item em $categoria: " . $e->getMessage()]);
                error_log("Erro ao excluir item: " . $e->getMessage());
                exit();
            } catch (Exception $e) {
                // Em caso de erro de exceção geral
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
                error_log("Erro: " . $e->getMessage());
                exit();
            }
        } else {
            // Em caso de falha na conexão com o banco de dados
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => "Erro ao conectar ao banco de dados."]);
            exit();
        }
    }
    

    public function adicionarCategoria($categoria, $descricao) {
        $conn = getConnection();
        if ($conn) {
            try {
                // Mapear as categorias para suas respectivas tabelas
                $tableMap = [
                    'unidade_medida' => 'unidade_medida',
                    'deposito' => 'deposito',
                    'segmento' => 'segmento',
                    'tipo_material' => 'tipo_material'
                ];
    
                if (!array_key_exists($categoria, $tableMap)) {
                    throw new Exception("Categoria inválida: $categoria");
                }
    
                $tabela = $tableMap[$categoria];
    
                // Inserir o valor na tabela correta
                $sql = "INSERT INTO $tabela (descricao) VALUES (:descricao)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':descricao', $descricao);
                $stmt->execute();
    
                header('Content-Type: application/json');
                echo json_encode(["status" => "success", "message" => "Categoria adicionada com sucesso"]);
                exit(); // Use exit() aqui também
            } catch (PDOException $e) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(["status" => "error", "message" => "Erro ao adicionar categoria em $categoria: " . $e->getMessage()]);
                error_log("Erro ao adicionar categoria: " . $e->getMessage());
                exit();
            } catch (Exception $e) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
                error_log("Erro: " . $e->getMessage());
                exit();
            }
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => "Erro ao conectar ao banco de dados."]);
            exit();
        }
    }
}

// Verificar qual ação deve ser executada
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['action']) && $_GET['action'] == 'listar' && isset($_GET['categoria'])) {
        $estoqueEdit = new EstoqueEdit();
        $categoria = $_GET['categoria'];
        $estoqueEdit->listarCategorias($categoria);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $estoqueEdit = new EstoqueEdit();

    if (isset($_POST['edit']) && isset($_POST['id']) && isset($_POST['descricaoNova']) && isset($_POST['categoria'])) {
        $id = $_POST['id'];
        $descricaoNova = $_POST['descricaoNova'];
        $categoria = $_POST['categoria'];
        $estoqueEdit->editarItem($id, $descricaoNova, $categoria);
    }

    if (isset($_POST['delete']) && isset($_POST['id']) && isset($_POST['categoria'])) {
        $id = $_POST['id'];
        $categoria = $_POST['categoria'];
        $estoqueEdit->excluirItem($id, $categoria);
    }

    if (isset($_POST['add']) && isset($_POST['descricao']) && isset($_POST['categoria'])) {
        $descricao = $_POST['descricao'];
        $categoria = $_POST['categoria'];
        $estoqueEdit->adicionarCategoria($categoria, $descricao);
    }
}
