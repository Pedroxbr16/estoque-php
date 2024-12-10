<?php
require_once 'estoqueController.php';

$controller = new EstoqueController();

if (isset($_GET['action']) && $_GET['action'] === 'enviarEmail') {
    try {
        $produtosCriticos = $controller->verificarEstoqueMinimo();

        if (!empty($produtosCriticos)) {
            $controller->enviarAlertaEstoque($produtosCriticos);
            echo json_encode(['success' => true, 'message' => 'Email enviado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhum produto com estoque crítico.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
    }
    exit;
}

// Default response if no action is provided
echo json_encode(['success' => false, 'message' => 'Nenhuma ação especificada.']);
