<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/homeadmnavbar.css">
    <link rel="stylesheet" href="../assets/css/relatorioest.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Document</title>
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
                            <a class="nav-link" href="relatorioest.php">
                                <span data-feather="shopping-cart"></span>
                                Produtos
                            </a>
                        </li>
                        
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

                   
                    
                    </ul>
                </div>
            </nav>

            <!--  fim do nav  -->


        </div>
    </div>


    <div class="container mt-5">
        <h2 class="text-center mb-4">Distribuição por Tipo de Material</h2>
        <div class="chart-container" style="position: relative; height:50vh; width:100%;">
            <canvas id="graficoEstoque"></canvas>
        </div>
    </div> 
       <div class="container mt-5">
        <h2 class="text-center mb-4">Distribuição por Estoque Minino e Estoque Atual</h2>
        <div class="chart-container" style="position: relative; height:50vh; width:100%;">
            <canvas id="graficoEstoqueMinimo"></canvas>
        </div>
    </div>
<!-- Modal para Gráfico Expandido -->
<div id="modalGrafico" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <canvas id="graficoExpandido" width="600" height="400"></canvas>
        <div id="filtros">
            <label for="filtroCategoria">Filtrar por Tipo de Material:</label>
            <select id="filtroCategoria">
                <option value="todos">Todos</option>
                <!-- Opções dinâmicas adicionadas pelo JavaScript -->
            </select>
        </div>
    </div>
</div>


    <script src="../assets/js/relatorioest.js">
    </script>
      <script src="../assets/js/relatorioestMM.js">
    </script>
    <!--  <script src="../assets/js/relatorioestmodal.js"> -->
    </script>
    <script>
    let homeUrl = '<?php echo $_SESSION['homeUrl'] ?? ""; ?>';
</script>
<script src="../assets/js/voltar_home.js"></script>

</body>

</html>