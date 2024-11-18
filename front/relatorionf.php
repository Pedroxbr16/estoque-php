<?php

require_once '../back/db.php'; // Certifique-se de ajustar o caminho para o arquivo que define a função getConnection()

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Vendas Diárias do Mês</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/homeadmnavbar.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!--  inicio do nav  -->

    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Nome da companhia</a>
        <input class="form-control form-control-dark w-50" type="text" placeholder="Search" aria-label="Search">
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="#">Sair</a>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 sidebar">
                <div class="sidebar-sticky">
                <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="homeadm.php">
                                <span data-feather="home"></span>
                                Dashboard <span class="sr-only">(atual)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="relatoriopd.php">
                                <span data-feather="file"></span>
                                Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="consultarnf.php">
                                <span data-feather="file"></span>
                                Consultar Nota Fiscala
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="relatorioest.php">
                                <span data-feather="shopping-cart"></span>
                                Produtos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="relatoriovend.php">
                                <span data-feather="users"></span>
                               Vendendor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="relatorionf.php">
                                <span data-feather="bar-chart-2"></span>
                                Relatórios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="home.php">
                                <span data-feather="layers"></span>
                                Integrações
                            </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Relatórios salvos</span>
                        <a class="d-flex align-items-center text-muted" href="#">
                            <span data-feather="plus-circle"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text"></span>
                                Neste mês
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text"></span>
                                Último trimestre
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text"></span>
                                Engajamento social
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text"></span>
                                Vendas do final de ano
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!--  fim do nav  -->

            <div class="container mt-5" style="padding-left: 20%;">
                <h1 class="text-center">Relatório de Vendas Diárias do Mês</h1>

                <form id="filterForm" class="row g-3 mb-4">
                    <!-- Opção de Tipo de Relatório -->
                    <div class="col-md-4">
                        <label for="relatorio" class="form-label">Tipo de Relatório</label>
                        <select id="relatorio" name="relatorio" class="form-control">
                            <option value="mensal">Relatório Mensal</option>
                            <option value="anual">Relatório Anual</option>
                            <option value="usuario">Relatório por Usuário</option>
                        </select>
                    </div>

                    <!-- Os filtros existentes -->
                    <div class="col-md-4">
                        <label for="mes" class="form-label">Mês</label>
                        <select id="mes" name="mes" class="form-control">
                            <option value="">Todos</option>
                            <option value="1">Janeiro</option>
                            <option value="2">Fevereiro</option>
                            <option value="3">Março</option>
                            <option value="4">Abril</option>
                            <option value="5">Maio</option>
                            <option value="6">Junho</option>
                            <option value="7">Julho</option>
                            <option value="8">Agosto</option>
                            <option value="9">Setembro</option>
                            <option value="10">Outubro</option>
                            <option value="11">Novembro</option>
                            <option value="12">Dezembro</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="ano" class="form-label">Ano</label>
                        <input type="number" id="ano" name="ano" class="form-control" value="2024">
                    </div>

                    <div class="col-md-6">
                        <label for="data_inicial" class="form-label">Data Inicial</label>
                        <input type="date" id="data_inicial" name="data_inicial" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="data_final" class="form-label">Data Final</label>
                        <input type="date" id="data_final" name="data_final" class="form-control">
                    </div>

                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" onclick="updateChart()">Aplicar Filtro</button>
                    </div>
                </form>


                <!-- Canvas para o gráfico -->
                <div>
                    <canvas id="salesChart"></canvas>
                </div>
            </div>











            <script>
                function setCurrentMonthAndYear() {
                    const today = new Date();
                    document.getElementById('mes').value = today.getMonth() + 1;
                    document.getElementById('ano').value = today.getFullYear();
                    updateChart();
                }

                function generateDaysList(mes) {
                    const diasNoMes = new Date(2024, mes, 0).getDate();
                    const days = [];
                    for (let i = 1; i <= diasNoMes; i++) {
                        days.push(i.toString());
                    }
                    return days;
                }

                const ctx = document.getElementById('salesChart').getContext('2d');
                let salesChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: generateDaysList(new Date().getMonth() + 1),
                        datasets: [{
                            label: 'Total de Vendas (R$)',
                            data: Array(31).fill(0),
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Total de Vendas (R$)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Dias do Mês'
                                }
                            }
                        }
                    }
                });

                function updateChart() {
                    const relatorio = document.getElementById('relatorio').value; // Tipo de relatório
                    const mes = document.getElementById('mes').value;
                    const ano = document.getElementById('ano').value;
                    const data_inicial = document.getElementById('data_inicial').value;
                    const data_final = document.getElementById('data_final').value;

                    const params = new URLSearchParams({
                        relatorio,
                        mes,
                        ano,
                        data_inicial,
                        data_final
                    });

                    // Faz a requisição para o backend com os filtros
                    fetch(`../back/VendasController.php?${params.toString()}`)
                        .then(response => response.json())
                        .then(data => {
                            const labels = data.map(item => item.label); // Exemplo de rótulos
                            const values = data.map(item => item.total_vendas);

                            // Atualiza o gráfico
                            updateChartGraph(labels, values);
                        })
                        .catch(error => console.error("Erro ao aplicar filtros:", error));
                }

                function updateChartGraph(labels, values) {
                    const ctx = document.getElementById("salesChart").getContext("2d");
                    if (salesChart) salesChart.destroy(); // Destroi o gráfico antigo
                    salesChart = new Chart(ctx, {
                        type: "bar",
                        data: {
                            labels,
                            datasets: [{
                                label: "Total de Vendas (R$)",
                                data: values,
                                backgroundColor: "rgba(75, 192, 192, 0.2)",
                                borderColor: "rgba(75, 192, 192, 1)",
                                borderWidth: 1
                            }]
                        }
                    });
                }


                setCurrentMonthAndYear();

                $(document).ready(function() {
                    $("#leftside-navigation .sub-menu > a").click(function(e) {
                        $("#leftside-navigation ul ul").slideUp(); // Fecha todos os submenus
                        if (!$(this).next().is(":visible")) { // Verifica se o submenu atual está visível
                            $(this).next().slideDown(); // Abre o submenu atual
                        }
                        e.stopPropagation(); // Evita que o evento se propague
                    });
                });
            </script>
</body>

</html>