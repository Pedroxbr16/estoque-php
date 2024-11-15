<?php
session_start();
include('../back/estoqueController.php');

require '../back/auth.php'; // Caminho para o arquivo auth.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$estoqueController = new EstoqueController();
$produtos = $estoqueController->buscarMateriais();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/consulta.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Consulta de Estoque</h1>

        <!-- Botão Voltar para a Home -->
        <div class="text-start mb-3">
            <a href="home.php" class="btn btn-secondary">← Voltar para Home</a>
        </div>

        <!-- Tabela de resultados -->
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Descrição</th>
                    <th>Unidade de Medida</th>
                    <th>Quantidade</th>
                    <th>Depósito</th>
                    <th>Estoque Mínimo</th>
                    <th>Estoque de Segurança</th>
                    <th>Tipo de Material</th>
                    <th>segmento</th>
                    
                </tr>
            </thead>
            <tbody>
    <?php foreach ($produtos as $produto): ?>
        <tr>
            <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
            <td><?php echo htmlspecialchars($produto['unidade_medida']); ?></td>
            <td><?php echo htmlspecialchars($produto['quantidade']); ?></td>
            <td><?php echo htmlspecialchars($produto['deposito']); ?></td>
            <td><?php echo htmlspecialchars($produto['estoque_minimo']); ?></td>
            <td><?php echo htmlspecialchars($produto['estoque_seguranca']); ?></td>
            <td><?php echo htmlspecialchars($produto['tipo_material']); ?></td>
            <td><?php echo htmlspecialchars($produto['segmento'] ?? 'N/A'); ?></td>

            <td>
                <a href="editar_produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
