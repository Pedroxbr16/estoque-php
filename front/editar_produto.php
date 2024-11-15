<?php
session_start();
include('../back/estoqueController.php');

require '../back/auth.php'; // Caminho para o arquivo auth.php

$estoqueController = new EstoqueController();
$id = $_GET['id'];

// Busca os dados do produto específico
$produto = $estoqueController->buscarMaterialPorId($id);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Editar Produto</h1>

        <!-- Formulário de Edição -->
        <form action="atualizar_produto.php" method="POST" class="row g-3">
            <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">

            <!-- Campo Descrição -->
            <div class="col-md-6">
                <label for="descricao" class="form-label">Descrição do Material:</label>
                <input type="text" name="descricao" class="form-control" id="descricao" value="<?php echo htmlspecialchars($produto['descricao']); ?>" required>
            </div>

            <!-- Campo Unidade de Medida -->
            <div class="col-md-6">
                <label for="unidade_medida" class="form-label">Unidade de Medida:</label>
                <select name="unidade_medida" class="form-select" id="unidade_medida" required>
                    <option value="unidade" <?php if ($produto['unidade_medida'] == 'unidade') echo 'selected'; ?>>Unidade</option>
                    <option value="litro" <?php if ($produto['unidade_medida'] == 'litro') echo 'selected'; ?>>Litro</option>
                    <option value="metro" <?php if ($produto['unidade_medida'] == 'metro') echo 'selected'; ?>>Metro</option>
                    <option value="quilo" <?php if ($produto['unidade_medida'] == 'quilo') echo 'selected'; ?>>Quilo</option>
                    <option value="caixa" <?php if ($produto['unidade_medida'] == 'caixa') echo 'selected'; ?>>Caixa</option>
                    <option value="pacote" <?php if ($produto['unidade_medida'] == 'pacote') echo 'selected'; ?>>Pacote</option>
                </select>
            </div>

            <!-- Campo Quantidade -->
            <div class="col-md-6">
                <label for="quantidade" class="form-label">Quantidade:</label>
                <input type="number" name="quantidade" class="form-control" id="quantidade" value="<?php echo htmlspecialchars($produto['quantidade']); ?>" required>
            </div>

            <!-- Campo Depósito -->
            <div class="col-md-6">
                <label for="deposito" class="form-label">Depósito:</label>
                <input type="text" name="deposito" class="form-control" id="deposito" value="<?php echo htmlspecialchars($produto['deposito']); ?>" required>
            </div>

            <!-- Campo Estoque Mínimo -->
            <div class="col-md-6">
                <label for="estoque_minimo" class="form-label">Estoque Mínimo:</label>
                <input type="number" name="estoque_minimo" class="form-control" id="estoque_minimo" value="<?php echo htmlspecialchars($produto['estoque_minimo']); ?>" required>
            </div>

            <!-- Campo Estoque de Segurança -->
            <div class="col-md-6">
                <label for="estoque_seguranca" class="form-label">Estoque de Segurança:</label>
                <input type="number" name="estoque_seguranca" class="form-control" id="estoque_seguranca" value="<?php echo htmlspecialchars($produto['estoque_seguranca']); ?>" required>
            </div>

            <!-- Campo Tipo de Material -->
            <div class="col-md-6">
                <label for="tipo_material" class="form-label">Tipo de Material:</label>
                <select name="tipo_material" class="form-select" id="tipo_material" required>
                    <option value="consumo" <?php if ($produto['tipo_material'] == 'consumo') echo 'selected'; ?>>Consumo</option>
                    <option value="escritorio" <?php if ($produto['tipo_material'] == 'escritorio') echo 'selected'; ?>>Escritório</option>
                    <option value="venda" <?php if ($produto['tipo_material'] == 'venda') echo 'selected'; ?>>Venda</option>
                </select>
            </div>

            <!-- Campo Segmento -->
            <div class="col-md-6">
                <label for="segmento" class="form-label">Grupo de Mercadorias:</label>
                <select name="segmento" class="form-select" id="segmento" required>
                    <option value="industrial" <?php if ($produto['segmento'] == 'industrial') echo 'selected'; ?>>Industrial</option>
                    <option value="comercial" <?php if ($produto['segmento'] == 'comercial') echo 'selected'; ?>>Comercial</option>
                    <option value="residencial" <?php if ($produto['segmento'] == 'residencial') echo 'selected'; ?>>Residencial</option>
                    <option value="hospitalar" <?php if ($produto['segmento'] == 'hospitalar') echo 'selected'; ?>>Hospitalar</option>
                    <option value="educacional" <?php if ($produto['segmento'] == 'educacional') echo 'selected'; ?>>Educacional</option>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success w-100">Salvar Alterações</button>
            </div>
        </form>
    </div>
</body>
</html>
