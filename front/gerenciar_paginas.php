<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Páginas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .modal-lg {
            max-width: 80%;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Botão Voltar -->
        <div class="mb-3">
            <a href="/estoque-php/front/painel-adm.php" class="btn btn-secondary">
                ← Voltar
            </a>
        </div>

        <div class="card text-center shadow-sm p-4">
            <div class="card-body">
                <h3 class="card-title">Gerenciar Páginas</h3>
                <button class="btn btn-primary m-2" id="adicionarPaginaBtn">Adicionar Nova Página</button>
                <div class="table-responsive mt-4">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome da Página</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="paginasTableBody">
                            <tr>
                                <td colspan="2" class="text-center">Carregando dados...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Adicionar ou Editar Página -->
    <div class="modal fade" id="modalPagina" tabindex="-1" aria-labelledby="modalPaginaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPaginaLabel">Adicionar Nova Página</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="paginaForm">
                        <div class="mb-3">
                            <label for="nomePagina" class="form-label">Nome da Página</label>
                            <input type="text" class="form-control" id="nomePagina" required>
                        </div>
                        <input type="hidden" id="paginaId">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript para Gerenciar Páginas -->
    <script src="../assets/js/gerenciar_paginas.js"></script>
</body>

</html>
