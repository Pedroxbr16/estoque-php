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
    <title>Relatório de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/relatorio.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('../back/routes/listarMateriais.php')
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
        <h1 class="text-center mb-4">Relatório de Estoque</h1>

        <!-- Botão Voltar para a Home -->
        <div class="text-start mb-3">
            <a href="home.php" class="btn btn-secondary">← Voltar para Home</a>
        </div>

        <!-- Filtro de relatório -->
        <form class="row g-3 mb-4">
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
                <label for="segmento" class="form-label">Segmento:</label>
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
                </tr>
            </thead>
            <tbody>
                <!-- Linhas de exemplo; você pode preencher com dados do banco de dados -->
                <tr>
                    <td>Material A</td>
                    <td>Unidade</td>
                    <td>50</td>
                    <td>Depósito 1</td>
                    <td>10</td>
                    <td>20</td>
                    <td>Consumo</td>
                    <td>Industrial</td>
                </tr>
                <tr>
                    <td>Material B</td>
                    <td>Litro</td>
                    <td>200</td>
                    <td>Depósito 2</td>
                    <td>30</td>
                    <td>50</td>
                    <td>Venda</td>
                    <td>Comercial</td>
                </tr>
                <!-- Mais linhas podem ser adicionadas dinamicamente -->
            </tbody>
        </table>
        <div class="col-12">
            <button type="button" id="buscarButton" class="btn btn-primary w-100">Buscar</button>
        </div>
    </div>

    <script>
        
    </script>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
</body>

</html>