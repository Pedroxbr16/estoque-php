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
        <button class="back-button">Voltar para Home</button>

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
        let homeUrl = '<?php echo $_SESSION['homeUrl'] ?? ""; ?>';
    </script>
    <script src="../assets/js/voltar_home.js"></script>
    <script src="../assets/js/emissao-notas.js"></script>
</body>

</html>