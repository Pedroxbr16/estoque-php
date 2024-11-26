<?php

require '../back/auth.php'; // Caminho para o arquivo auth.php
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/cadastro.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Cadastro de Estoque</h1>

        <!-- Botão Voltar para a Home -->
        <div class="text-start mb-3">
        <button class="back-button">Voltar para Home</button>
        </div>

        <!-- Formulário para cadastro de estoque -->
        <form id="produtoForm" action="../back/controller/cadastro-estoque.php" method="POST" class="row g-3">
    <div class="col-md-6 position-relative">
        <label for="descricao" class="form-label">Descrição do Material:</label>
        <input type="text" name="descricao" class="form-control" id="descricao" required autocomplete="off">
        <ul id="sugestoesDescricao" class="list-group position-absolute"></ul>
    </div>

    <div class="col-md-6">
        <label for="unidade_medida" class="form-label">Unidade de Medida:</label>
        <select name="unidade_medida" class="form-select" id="unidade_medida" data-categoria="unidade_medida" required>
            <option value="" selected>Carregando...</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="quantidade" class="form-label">Quantidade:</label>
        <input type="number" name="quantidade" class="form-control" id="quantidade" required>
    </div>

    <div class="col-md-6 position-relative">
        <label for="deposito" class="form-label">Depósito:</label>
        <select name="deposito" class="form-select" id="deposito" data-categoria="deposito" required>
            <option value="" selected>Carregando...</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="estoque_minimo" class="form-label">Estoque Mínimo:</label>
        <input type="number" name="estoque_minimo" class="form-control" id="estoque_minimo" required>
    </div>

    <div class="col-md-6">
        <label for="estoque_seguranca" class="form-label">Estoque de Segurança:</label>
        <input type="number" name="estoque_seguranca" class="form-control" id="estoque_seguranca" required>
    </div>

    <div class="col-md-6">
        <label for="tipo_material" class="form-label">Tipo de Material:</label>
        <select name="tipo_material" class="form-select" id="tipo_material" data-categoria="tipo_material" required>
            <option value="" selected>Carregando...</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="segmento" class="form-label">Grupo de Mercadorias:</label>
        <select name="segmento" class="form-select" id="segmento" data-categoria="segmento" required>
            <option value="" selected>Carregando...</option>
        </select>
    </div>

    <div class="col-12">
        <button type="submit" name="action" value="cadastrar" class="btn btn-success w-100">Cadastrar</button>
    </div>
</form>

        <!-- Área para exibir mensagens de sucesso ou erro -->
        <?php
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') {
                echo "<p class='alert alert-success mt-3'>Material cadastrado com sucesso!</p>";
            } elseif ($_GET['status'] == 'error') {
                echo "<p class='alert alert-danger mt-3'>Erro ao cadastrar o material. Tente novamente.</p>";
            }
        }
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/cadastro_estoque.js"></script>
    <script>
    let homeUrl = '<?php echo $_SESSION['homeUrl'] ?? ""; ?>';
</script>
<script src="../assets/js/voltar_home.js"></script>

</body>

</html>