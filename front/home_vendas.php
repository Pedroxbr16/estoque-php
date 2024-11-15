<?php
session_start();

require '../back/auth.php'; // Caminho para o arquivo auth.php

?>
<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Sistema de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="../assets/styles.css">
</head>

<body>

    <?php
    // Exibir o SweetAlert se o parâmetro de erro estiver presente
    if (isset($_GET['error']) && $_GET['error'] === 'no_permission') {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Exibir o alerta com SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Acesso Negado',
                text: 'Você não tem permissão para acessar essa página.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Remover o parâmetro 'error' da URL
                const url = new URL(window.location);
                url.searchParams.delete('error');
                window.history.replaceState(null, '', url);
            });
        });
    </script>";
    }
    ?>
    <!-- Navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Sistema de Vendas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="consulta_depositoVD.php">Consulta de Estoque</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="emissao_notas.php">Emissão de Notas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../back/usuariocontroller.php?action=logout">Sair</a>
                    </li>
                    <!-- Informações do usuário -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="usuarioDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['usuario_nome']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="usuarioDropdown">
                            <li class="dropdown-item-text">
                                <?php echo $_SESSION['usuario_funcao']; ?>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="../back/usuariocontroller.php?action=logout">Sair</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Seção principal -->
    <header class="bg-light text-center py-5">
        <div class="container">
            <h1 class="display-4">Bem-vindo ao Sistema de Vendas</h1>
            <p class="lead">Gerencie facilmente as suas vendas e emita notas fiscais de maneira rápida e prática.</p>
            <a href="emissao_notas.php" class="btn btn-primary btn-lg mt-3">Emitir Nota Fiscal</a>
            <a href="consulta_depositoVD.php" class="btn btn-outline-secondary btn-lg mt-3">Consultar Estoque</a>
        </div>
    </header>

    <!-- Seções adicionais -->
    <section class="container my-5">
        <div class="row text-center">
            <div class="col-md-6">
                <h3>Consulta de Estoque</h3>
                <p>Verifique a disponibilidade de produtos para venda e mantenha o controle do seu inventário.</p>
            </div>
            <div class="col-md-6">
                <h3>Emissão de Notas Fiscais</h3>
                <p>Emita notas fiscais de forma rápida para facilitar as suas operações de venda.</p>
            </div>
        </div>
    </section>

    <!-- Rodapé -->
    <!-- <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2024 P.E.M Tech. Todos os direitos reservados.</p>
    </footer> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>