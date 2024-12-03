<?php
include_once '../back/auth.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Consulta de Estoque</h1>

        <!-- Botão Voltar para a Home -->
        <div class="text-start mb-3">
            <button class="back-button">Voltar para Home</button>
        </div>

        <!-- Formulário de Filtros -->
        <form id="filterForm" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="descricao" class="form-label">Descrição do Material:</label>
                <input type="text" class="form-control" id="descricao" placeholder="Digite a descrição">
                <div class="resultados"></div>
            </div>
            <div class="col-md-4">
                <label for="tipo_material" class="form-label">Tipo de Material:</label>
                <select class="form-select" id="tipo_material">
                    <option value="" selected>Todos</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="segmento" class="form-label">Grupo de Mercadorias:</label>
                <select class="form-select" id="segmento">
                    <option value="" selected>Todos</option>
                </select>
            </div>
            <div class="col-12">
                <button type="button" id="buscarButton" class="btn btn-primary w-100">Buscar</button>
            </div>
            <!-- Botão para exportar para PDF -->
            <div class="text-end mb-3">
                <a href="../back/scripts/gerarRelatorioDiario.php"></a>
                <button type="button" id="exportarPDF" class="btn btn-danger">Gerar Relatório</button>
            </div>
        </form>

        <!-- Tabela de Resultados -->
        <div class="table-responsive">
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
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="resultTable">
                    <tr>
                        <td colspan="9" class="text-center">Carregando dados...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="pagination" class="d-flex justify-content-center mt-3"></div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/consulta-deposito.js"></script>
    <script>
        let homeUrl = '<?php echo $_SESSION['homeUrl'] ?? ""; ?>';
    </script>
    <script src="../assets/js/voltar_home.js"></script>
</body>

</html>
