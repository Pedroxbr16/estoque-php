<?php
session_start();
require '../back/auth.php'; // Caminho para o arquivo auth.php

include('../back/db.php');
include_once('../back/estoqueController.php');

// Inicializa o controlador de estoque
$estoqueController = new EstoqueController();
$produtos = $estoqueController->buscarMateriais();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emissão de Nota Fiscal</title>
    <link rel="stylesheet" href="../assets/css/emissaonotas.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        
    </style>
</head>

<body>
    <div class="container">
        <button class="back-button" onclick="window.location.href='home_vendas.php'">Voltar para Home</button>

        <h1>Emissão de Nota Fiscal</h1>

        <!-- Exibe a mensagem usando SweetAlert, se houver uma mensagem presente na URL -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const message = urlParams.get('message');

                if (message) {
                    Swal.fire({
                        title: 'Aviso!',
                        text: message,
                        icon: message.includes("sucesso") ? 'success' : 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Remove os parâmetros da URL após exibir o alerta
                        const newURL = window.location.origin + window.location.pathname;
                        window.history.replaceState(null, '', newURL);
                    });
                }
            });
        </script>

        <form action="../back/estoqueController.php?action=emitirNota" method="POST" onsubmit="prepareFormData(); gerarPDF(); ">
            <h2>Seleção de Produtos</h2>

            <label for="produto">Produto:</label>
            <select id="produto" name="produto_id">
                <?php foreach ($produtos as $produto): ?>
                    <option value="<?= htmlspecialchars($produto['id'], ENT_QUOTES, 'UTF-8') ?>" data-preco="<?= htmlspecialchars($produto['preco'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($produto['descricao'], ENT_QUOTES, 'UTF-8') ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="quantidade">Quantidade:</label>
            <input type="number" name="quantidade" id="quantidade" value="1" min="1">

            <button type="button" onclick="adicionarProduto()">Adicionar Produto</button>

            <input type="hidden" id="itens" name="itens">

            <h2>Itens da Nota</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="tabela-itens"></tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td id="total">R$ 0,00</td>
                    </tr>
                </tfoot>
            </table>

            <button type="submit">Emitir Nota Fiscal e PDF</button>
            <button type="button" class="reset-button" onclick="resetarNota()">Resetar Nota</button>
        </form>
    </div>

    <script src="../assets/js/editar-pdt.js"></script>
</body>

</html>
V