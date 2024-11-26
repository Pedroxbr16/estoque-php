<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/homeadmnavbar.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Relatório de Vendedores</title>
</head>

<body>
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
                                Consultar Nota Fiscal
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
                                Vendedor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="relatorionf.php">
                                <span data-feather="bar-chart-2"></span>
                                Relatórios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="homeadmEV.php">
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

            <main class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h1 class="h2">Relatório de Vendedores</h1>

                <!-- Seleção do Vendedor -->
                <div class="mb-3">
                    <label for="vendedor" class="form-label">Selecione um Vendedor:</label>
                    <select id="vendedor" class="form-select" >
                        <option value="">Selecione um vendedor</option>
                    </select>
                </div>

                <!-- Filtros de Relatório -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="ano" class="form-label">Ano</label>
                        <select id="ano" class="form-select" onchange="buscarRelatorioVendas()">
                            <option value="">Selecione o ano</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="mes" class="form-label">Mês</label>
                        <select id="mes" class="form-select" onchange="buscarRelatorioVendas()">
                            <option value="">Selecione o mês</option>
                        </select>
                    </div>
                </div>

                <!-- Gráfico de Vendas -->
                <div>
                    <canvas id="graficoVendas" width="400" height="200"></canvas>
                </div>
            </main>
        </div>
    </div>

    <script src="../assets/js/voltar_home.js"></script>
    <script src="../assets/js/relatoriovend.js"></script>

</body>

</html>
