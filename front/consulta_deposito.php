<?php
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('../back/depositoController.php?action=listarMateriais')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector("table tbody");
                tbody.innerHTML = ""; // Limpa as linhas anteriores

                data.forEach(material => {
                    const row = document.createElement("tr");

                    row.innerHTML = `
                    <td>${material.descricao}</td>
                    <td>${material.unidade_medida}</td>
                    <td>${material.quantidade}</td>
                    <td>${material.deposito}</td>
                    <td>${material.estoque_minimo}</td>
                    <td>${material.estoque_seguranca}</td>
                    <td>${material.tipo_material}</td>
                    <td>${material.segmento}</td>
                `;

                    tbody.appendChild(row);
                });
            })
            .catch(error => console.error("Erro ao buscar os dados:", error));
    });
</script>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Consulta de Estoque</h1>

        <!-- Botão Voltar para a Home -->
        <div class="text-start mb-3">
            <a href="home.php" class="btn btn-secondary">← Voltar para Home</a>
        </div>

        <form id="filterForm" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="descricao" class="form-label">Descrição do Material:</label>
                <input type="text" class="form-control" id="descricao" placeholder="Digite a descrição">
            </div>
            <div class="col-md-4">
                <label for="tipo_material" class="form-label">Tipo de Material:</label>
                <select class="form-select" id="tipo_material">
                    <option selected>Todos</option>
                    <option value="consumo">Consumo</option>
                    <option value="escritorio">Escritório</option>
                    <option value="venda">Venda</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="segmento" class="form-label">Grupo de Mercadorias:</label>
                <select class="form-select" id="segmento">
                    <option selected>Todos</option>
                    <option value="industrial">Industrial</option>
                    <option value="comercial">Comercial</option>
                    <option value="residencial">Residencial</option>
                    <option value="hospitalar">Hospitalar</option>
                    <option value="educacional">Educacional</option>
                </select>
            </div>
        </form>

        <!-- Tabela de resultados -->
        <div class="table-responsive">
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
                        <th>Segmento</th>
                        <th>Ações</th>
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
            <div class="col-12">
            <button type="button" id="buscarButton" class="btn btn-primary w-100">Buscar</button>
        </div>
        </div>
    </div>
    <script>
        document.getElementById("buscarButton").addEventListener("click", function() {
            const descricao = document.getElementById("descricao").value;
            const tipoMaterial = document.getElementById("tipo_material").value;
            const segmento = document.getElementById("segmento").value;

            // Monta a URL com os parâmetros de filtro
            const url = `../back/depositoController.php?action=listarMateriais&descricao=${encodeURIComponent(descricao)}&tipo_material=${encodeURIComponent(tipoMaterial)}&segmento=${encodeURIComponent(segmento)}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector("table tbody");
                    tbody.innerHTML = "";  // Limpa as linhas anteriores

                    data.forEach(material => {
                        const row = document.createElement("tr");

                        row.innerHTML = `
                            <td>${material.descricao}</td>
                            <td>${material.unidade_medida}</td>
                            <td>${material.quantidade}</td>
                            <td>${material.deposito}</td>
                            <td>${material.estoque_minimo}</td>
                            <td>${material.estoque_seguranca}</td>
                            <td>${material.tipo_material}</td>
                            <td>${material.segmento}</td>
                        `;

                        tbody.appendChild(row);
                    });
                })
                .catch(error => console.error("Erro ao buscar os dados:", error));
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

