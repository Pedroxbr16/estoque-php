<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emissão de Nota Fiscal</title>
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
            max-width: 600px;
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

        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Botões */
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

        /* Botão de voltar e resetar */
        .back-button, .reset-button {
            background-color: #6c757d;
            color: #fff;
            margin-top: 10px;
        }

        .back-button:hover, .reset-button:hover {
            background-color: #5a6268;
        }

        /* Tabela de itens */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #004085;
            color: #fff;
        }

        td {
            background-color: #f9f9f9;
        }

        tfoot td {
            font-weight: bold;
            background-color: #e9ecef;
        }
    </style>

    <script>
        let produtos = [];
        
        function adicionarProduto() {
            const produtoSelect = document.getElementById("produto");
            const quantidade = parseInt(document.getElementById("quantidade").value);
            const produtoSelecionado = produtoSelect.options[produtoSelect.selectedIndex];
            const nome = produtoSelecionado.text.split(" - ")[0];
            const preco = parseFloat(produtoSelecionado.getAttribute("data-preco"));
            const subtotal = preco * quantidade;

            produtos.push({ nome, quantidade, preco, subtotal });
            atualizarTabela();
        }

        function atualizarTabela() {
            const tabela = document.getElementById("tabela-itens");
            tabela.innerHTML = "";
            let total = 0;

            produtos.forEach((produto) => {
                total += produto.subtotal;
                tabela.innerHTML += `<tr>
                    <td>${produto.nome}</td>
                    <td>${produto.quantidade}</td>
                    <td>R$ ${produto.preco.toFixed(2)}</td>
                    <td>R$ ${produto.subtotal.toFixed(2)}</td>
                </tr>`;
            });

            document.getElementById("total").textContent = "R$ " + total.toFixed(2);
            document.getElementById("itens").value = JSON.stringify(produtos);
        }

        function resetarNota() {
            produtos = [];
            atualizarTabela();
            document.getElementById("total").textContent = "R$ 0,00";
        }

        async function gerarPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Cabeçalho do PDF
            doc.setFontSize(16);
            doc.text("Nota Fiscal", 105, 10, { align: "center" });

            // Cabeçalho da Tabela
            doc.setFontSize(12);
            doc.text("Produto", 10, 30);
            doc.text("Quantidade", 80, 30);
            doc.text("Preço Unitário", 120, 30);
            doc.text("Subtotal", 170, 30);

            // Itens da Nota
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

            // Total
            doc.setFontSize(12);
            doc.text("Total: R$ " + total.toFixed(2), 170, posY + 10);

            // Baixar o PDF
            doc.save("nota_fiscal.pdf");
        }
    </script>
</head>
<body>

<div class="container">
    <!-- Botão de Voltar para o Home -->
    <button class="back-button" onclick="window.location.href='home_vendas.php'">Voltar para Home</button>

    <h1>Emissão de Nota Fiscal</h1>

    <form onsubmit="gerarPDF(); return false;">

        <!-- Seleção de Produtos -->
        <label for="produto">Produto:</label>
        <select id="produto">
            <option value="1" data-preco="100.00">Produto 1 - R$ 100,00</option>
            <option value="2" data-preco="200.00">Produto 2 - R$ 200,00</option>
            <option value="3" data-preco="300.00">Produto 3 - R$ 300,00</option>
        </select>
        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" value="1" min="1">
        <button type="button" onclick="adicionarProduto()">Adicionar Produto</button>

        <!-- Tabela de Itens -->
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

        <br><br>
        <button type="submit">Emitir Nota Fiscal em PDF</button>
        <button type="button" class="reset-button" onclick="resetarNota()">Resetar Nota</button>
    </form>
</div>

</body>
</html>