<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta por Depósito</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <div class="container">
        <h1>Consulta de Estoque por Depósito</h1>
        <form action="back/estoqueController.php" method="GET">
            <label>Depósito ou Setor:</label>
            <input type="text" name="deposito" required>
            
            <button type="submit" name="action" value="consultar">Consultar</button>
        </form>

        <!-- Aqui a lista de estoque será exibida após a consulta -->
        <div class="estoque-lista">
            <?php
            // Exemplo de código para mostrar os resultados da consulta
            if (isset($_GET['deposito'])) {
                include('back/estoqueController.php');
                $controller = new EstoqueController();
                $materiais = $controller->listarMateriaisPorDeposito($_GET['deposito']);
                
                if (!empty($materiais)) {
                    echo "<table>";
                    echo "<tr><th>Descrição</th><th>Unidade</th><th>Quantidade</th><th>Depósito</th></tr>";
                    foreach ($materiais as $material) {
                        echo "<tr>";
                        echo "<td>" . $material['descricao'] . "</td>";
                        echo "<td>" . $material['unidade_medida'] . "</td>";
                        echo "<td>" . $material['quantidade'] . "</td>";
                        echo "<td>" . $material['deposito'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>Nenhum material encontrado para o depósito: " . htmlspecialchars($_GET['deposito']) . "</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
