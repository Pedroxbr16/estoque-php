<?php

session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Notas Fiscais</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-size: .875rem;
        }
        .navbar-brand {
            padding-top: .75rem;
            padding-bottom: .75rem;
            background-color: rgba(0, 0, 0, .25);
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">Minha Empresa</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <input class="form-control form-control-dark w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search">
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="#">Sign out</a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3 sidebar-sticky">
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
                            <a class="nav-link" href="consultarnf.php>
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
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Notas Fiscais</h1>
                </div>

                <div class="container mt-3">
                    <a href="home.php" class="btn btn-primary mb-3">Voltar para Home</a>
                    <div class="container mt-5">
                        <h3>Filtrar Gráfico de Notas Fiscais</h3>
                        <form id="filtroForm" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="tipoFiltro" class="form-label">Selecione o Filtro:</label>
                                    <select class="form-select" id="tipoFiltro" name="tipoFiltro">
                                        <option value="mes">Total de Notas por Mês</option>
                                        <option value="ano">Total de Notas por Ano</option>
                                        <option value="usuario">Total de Notas por Usuário</option>
                                    </select>
                                </div>
                                <div class="col-md-4 align-self-end">
                                    <button type="button" class="btn btn-primary" onclick="atualizarGrafico()">Aplicar Filtro</button>
                                </div>
                            </div>
                        </form>
                        <h3 id="tituloGrafico">Total de Notas Fiscais por Mês</h3>
                        <canvas id="notasPorMes"></canvas>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let notasPorMesChart;

    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('notasPorMes').getContext('2d');
        notasPorMesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [], // Labels preenchidas com dados do backend
                datasets: [{
                    label: 'Total de Notas',
                    data: [], // Dados preenchidos do backend
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

    async function atualizarGrafico() {
        const tipoFiltro = document.getElementById('tipoFiltro').value;
        let url = '';

        switch (tipoFiltro) {
            case 'mes':
                url = 'api_notas.php?action=notasPorMes';
                break;
            case 'ano':
                url = 'api_notas.php?action=notasPorAno';
                break;
            case 'usuario':
                url = 'api_notas.php?action=notasPorUsuario';
                break;
        }

        try {
            const response = await fetch(url);
            const data = await response.json();

            if (data.error) {
                console.error(data.error);
                return;
            }

            // Processar dados para o Chart.js
            const labels = data.labels;
            const valores = data.values;

            notasPorMesChart.data.labels = labels;
            notasPorMesChart.data.datasets[0].data = valores;
            notasPorMesChart.update();

        } catch (error) {
            console.error('Erro ao atualizar o gráfico:', error);
        }
    }
</script>

</body>
</html>
