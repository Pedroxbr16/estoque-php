<?php
session_start();
require '../back/auth.php'; // Caminho para o arquivo auth.php
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/relatorio.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Relatório de Estoque</h1>

        <!-- Botão Voltar para a Home -->
        <div class="text-start mb-3">
            <a href="home.php" class="btn btn-secondary">← Voltar para Home</a>
        </div>

        <!-- Filtro de relatório -->
        <form class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="descricao" class="form-label">Descrição do Material:</label>
                <input type="text" class="form-control" id="descricao" placeholder="Digite a descrição">
            </div>
            <div class="col-md-4">
                <label for="tipo_material" class="form-label">Tipo de Material:</label>
                <select class="form-select" id="tipo_material">
                    <option selected>Todos</option>
                    <option value="consumo">Consumo</option>
                    <option value="escritorio">Escritório</option>
                    <option value="venda">Venda</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="segmento" class="form-label">Segmento:</label>
                <select class="form-select" id="segmento">
                    <option selected>Todos</option>
                    <option value="industrial">Industrial</option>
                    <option value="comercial">Comercial</option>
                    <option value="residencial">Residencial</option>
                    <option value="hospitalar">Hospitalar</option>
                    <option value="educacional">Educacional</option>
                </select>
            </div>
            <div class="col-12">
                <button type="button" class="btn btn-primary w-100">Buscar</button>
            </div>
        </form>

        <!-- Botão para exportar para PDF -->
        <div class="text-end mb-3">
            <button onclick="exportToPDF()" class="btn btn-danger">Exportar para PDF</button>
        </div>

        <!-- Tabela de resultados -->
        <table class="table table-striped table-bordered" id="relatorioTable">
            <thead class="table-dark">
                <tr>
                    <th>Descrição</th>
                    <th>Unidade de Medida</th>
                    <th>Quantidade</th>
                    <th>Depósito</th>
                    <th>Estoque Mínimo</th>
                    <th>Estoque de Segurança</th>
                    <th>Tipo de Material</th>
                    <th>Segmento</th>
                </tr>
            </thead>
            <tbody>
                <!-- Linhas de exemplo; você pode preencher com dados do banco de dados -->
                <tr>
                    <td>Material A</td>
                    <td>Unidade</td>
                    <td>50</td>
                    <td>Depósito 1</td>
                    <td>10</td>
                    <td>20</td>
                    <td>Consumo</td>
                    <td>Industrial</td>
                </tr>
                <tr>
                    <td>Material B</td>
                    <td>Litro</td>
                    <td>200</td>
                    <td>Depósito 2</td>
                    <td>30</td>
                    <td>50</td>
                    <td>Venda</td>
                    <td>Comercial</td>
                </tr>
                <!-- Mais linhas podem ser adicionadas dinamicamente -->
            </tbody>
        </table>
    </div>

    <script>
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Adicionando título ao PDF
            doc.setFontSize(18);
            doc.text("Relatório de Estoque", 14, 20);

            // Adicionando tabela ao PDF
            let rows = [];
            const table = document.getElementById("relatorioTable");
            for (let i = 1; i < table.rows.length; i++) {
                let row = [];
                for (let j = 0; j < table.rows[i].cells.length; j++) {
                    row.push(table.rows[i].cells[j].innerText);
                }
                rows.push(row);
            }

            // Configuração de largura das colunas
            const columnWidths = [35, 25, 20, 30, 25, 30, 30, 30];
            doc.autoTable({
                head: [["Descrição", "Unidade de Medida", "Quantidade", "Depósito", "Estoque Mínimo", "Estoque de Segurança", "Tipo de Material", "Segmento"]],
                body: rows,
                startY: 30,
                columnStyles: { 0: { cellWidth: columnWidths[0] } },
            });

            // Salva o PDF
            doc.save("Relatorio_Estoque.pdf");
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
</body>
</html>
