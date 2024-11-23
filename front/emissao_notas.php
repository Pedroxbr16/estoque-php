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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        /* Estilos globais */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        h1 {
            color: #004085;
            text-align: center;
        }

        .container {
            max-width: 800px;
            width: 100%;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 15px;
            margin: 10px 5px 0 0;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"] {
            background-color: #004085;
            color: #fff;
        }

        button[type="button"] {
            background-color: #007bff;
            color: #fff;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        button[type="button"]:hover {
            background-color: #0056b3;
        }

        .back-button,
        .reset-button {
            background-color: #6c757d;
            color: #fff;
            margin-top: 10px;
        }

        .back-button:hover,
        .reset-button:hover {
            background-color: #5a6268;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.9em;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #004085;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:nth-child(even) td {
            background-color: #e9f1f7;
        }

        tfoot td {
            font-weight: bold;
            background-color: #e9ecef;
        }

        /* Responsividade */
        @media screen and (max-width: 600px) {
            table {
                font-size: 0.8em;
            }

            th,
            td {
                padding: 8px;
            }
        }
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

    <script>
        let produtos = []; // Array para armazenar os produtos adicionados

        function adicionarProduto() {
            const produtoSelect = document.getElementById("produto");
            const quantidade = parseInt(document.getElementById("quantidade").value);
            const produtoId = produtoSelect.value;

            // Verifica se o produto já existe no array 'produtos'
            let quantidadeTotalSolicitada = quantidade;
            const produtoExistente = produtos.find(produto => produto.produto_id === produtoId);
            if (produtoExistente) {
                quantidadeTotalSolicitada += produtoExistente.quantidade; // Soma a quantidade existente na nota com a nova solicitada
            }

            // Realizar uma verificação de estoque usando uma requisição AJAX
            fetch(`../back/estoqueController.php?action=verificarEstoque&produto_id=${produtoId}&quantidade=${quantidadeTotalSolicitada}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Se houver estoque suficiente, adicionar o produto
                        const produtoSelecionado = produtoSelect.options[produtoSelect.selectedIndex];
                        const nome = produtoSelecionado.text.split(" - ")[0];
                        const preco = parseFloat(produtoSelecionado.getAttribute("data-preco"));

                        if (isNaN(preco)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Preço não encontrado para o produto selecionado.'
                            });
                            return;
                        }

                        if (produtoExistente) {
                            // Atualiza a quantidade e o subtotal do produto existente
                            produtoExistente.quantidade += quantidade;
                            produtoExistente.subtotal = produtoExistente.quantidade * produtoExistente.preco;
                        } else {
                            // Adiciona o novo produto ao array
                            const subtotal = preco * quantidade;
                            produtos.push({
                                produto_id: produtoId,
                                nome,
                                quantidade,
                                preco,
                                subtotal
                            });
                        }

                        atualizarTabela();
                    } else {
                        // Se não houver estoque suficiente, exibir um alerta
                        Swal.fire({
                            icon: 'error',
                            title: 'Estoque Insuficiente',
                            text: 'Não há quantidade suficiente em estoque para este produto.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro ao verificar o estoque:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Erro ao verificar o estoque. Tente novamente.'
                    });
                });
        }


        function atualizarTabela() {
            const tabela = document.getElementById("tabela-itens");
            tabela.innerHTML = ""; // Limpa a tabela antes de atualizar
            let total = 0;

            // Itera sobre o array `produtos` para exibir cada item
            produtos.forEach((produto) => {
                total += produto.subtotal;
                tabela.innerHTML += `
                    <tr>
                        <td>${produto.nome}</td>
                        <td>${produto.quantidade}</td>
                        <td>R$ ${produto.preco.toFixed(2)}</td>
                        <td>R$ ${produto.subtotal.toFixed(2)}</td>
                    </tr>
                `;
            });

            // Atualiza o valor total na interface para visualização
            document.getElementById("total").textContent = "R$ " + total.toFixed(2);

            // Armazena os produtos no campo oculto para envio no formulário
            document.getElementById("itens").value = JSON.stringify(produtos);
        }

        function resetarNota() {
            produtos = [];
            atualizarTabela();
        }

        function prepareFormData() {
            document.getElementById("itens").value = JSON.stringify(produtos);
        }

        function gerarPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(16);
            doc.text("Nota Fiscal", 105, 10, {
                align: "center"
            });
            doc.setFontSize(12);
            doc.text("Produto", 10, 30);
            doc.text("Quantidade", 80, 30);
            doc.text("Preço Unitário", 120, 30);
            doc.text("Subtotal", 170, 30);

            let posY = 40;
            let total = 0;
            produtos.forEach((produto) => {
                doc.text(produto.nome, 10, posY);
                doc.text(String(produto.quantidade), 80, posY);
                doc.text("R$ " + produto.preco.toFixed(2), 120, posY);
                doc.text("R$ " + produto.subtotal.toFixed(2), 170, posY);
                total += produto.subtotal;
                posY += 10;
            });

            doc.setFontSize(12);
            doc.text("Total: R$ " + total.toFixed(2), 170, posY + 10);

            doc.save("nota_fiscal.pdf");
        }
    </script>
</body>

</html>
V