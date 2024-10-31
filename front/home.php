<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Sistema de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <!-- Navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Sistema de Estoque</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro_estoque.php">Cadastro de Estoque</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="consulta_deposito.php">Consulta Estoque</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="relatorio.php">Relatórios</a>
                    </li>
                   
                </ul>
            </div>
        </div>
    </nav>

    <!-- Seção principal -->
    <header class="bg-light text-center py-5">
        <div class="container">
            <h1 class="display-4">Bem-vindo ao Sistema de Estoque</h1>
            <p class="lead">Gerencie facilmente o seu estoque, cadastre materiais e acompanhe relatórios de desempenho.</p>
            <a href="cadastro.html" class="btn btn-primary btn-lg mt-3">Cadastrar Material</a>
            <a href="relatorios.html" class="btn btn-outline-secondary btn-lg mt-3">Visualizar Relatórios</a>
        </div>
    </header>

    <!-- Seções adicionais -->
    <section class="container my-5">
        <div class="row text-center">
            <div class="col-md-4">
                <h3>Cadastro Simples</h3>
                <p>Cadastre novos materiais e acompanhe o estoque de forma intuitiva e organizada.</p>
            </div>
            <div class="col-md-4">
                <h3>Relatórios Detalhados</h3>
                <p>Visualize relatórios completos para monitorar o desempenho e manter o controle do estoque.</p>
            </div>
            <div class="col-md-4">
                <h3>Facilidade de Acesso</h3>
                <p>Acesse rapidamente as informações com uma interface limpa e funcional.</p>
            </div>
        </div>
    </section>

    <!-- Rodapé
    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2024 P.E.M Tech. Todos os direitos reservados.</p>
    </footer> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
