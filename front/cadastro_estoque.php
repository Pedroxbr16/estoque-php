<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Estoque</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Estoque</h1>
        <!-- Formulário para cadastro de estoque -->
        <form action="back/estoqueController.php" method="POST">
            <label>Descrição do Material:</label>
            <input type="text" name="descricao" required>

            <label>Unidade de Medida:</label>
            <input type="text" name="unidade_medida" required>

            <label>Quantidade:</label>
            <input type="number" name="quantidade" required>

            <label>Depósito:</label>
            <input type="text" name="deposito" required>

            <label>Estoque Mínimo:</label>
            <input type="number" name="estoque_minimo" required>

            <label>Estoque de Segurança:</label>
            <input type="number" name="estoque_seguranca" required>

            <label>Tipo de Material:</label>
            <select name="tipo_material" required>
                <option value="consumo">Consumo</option>
                <option value="escritorio">Escritório</option>
                <option value="venda">Venda</option>
            </select>

            <button type="submit" name="action" value="cadastrar">Cadastrar</button>
        </form>
        
        <!-- Área para exibir mensagens de sucesso ou erro -->
        <?php
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') {
                echo "<p class='success'>Material cadastrado com sucesso!</p>";
            } elseif ($_GET['status'] == 'error') {
                echo "<p class='error'>Erro ao cadastrar o material. Tente novamente.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
