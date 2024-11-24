<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Pessoal de Vendas</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/relatorio_vendedor.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container">
        <h2>Relatório Pessoal de Vendas</h2>
        <div id="filtros" class="text-center mb-4">
            <label for="tipo-relatorio">Escolha o Tipo de Relatório:</label>
            <select id="tipo-relatorio" class="form-select">
                <option value="semanal">Semanal</option>
                <option value="mensal">Mensal</option>
                <option value="anual">Anual</option>
            </select>
        </div>

        <div id="resumo-vendas" class="text-center">
    <h3>Resumo de Vendas</h3>
    <div id="resumo-semana" style="display: none;">
        <p>Vendas da Semana: R$ <span id="vendas-semana">0.00</span></p>
    </div>
    <div id="resumo-mes" style="display: none;">
        <p>Vendas do Mês: R$ <span id="vendas-mes">0.00</span></p>
    </div>
    <div id="resumo-anual" style="display: none;">
        <p>Vendas do Ano: R$ <span id="vendas-anual">0.00</span></p>
    </div>
</div>

        <div id="grafico-progresso" class="mt-4">
            <canvas id="graficoMeta"></canvas>
        </div>
    </div>

    <!-- Inclua o script JavaScript após o conteúdo do corpo -->
    <script src="../assets/js/relatorio_vendedor.js"></script>
</body>

</html>
