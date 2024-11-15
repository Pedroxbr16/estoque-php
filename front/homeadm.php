<?php
ini_set('memory_limit', '256M');
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Notas Fiscais</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-size: .875rem;
        }
        .navbar-brand {
            padding-top: .75rem;
            padding-bottom: .75rem;
            background-color: rgba(0, 0, 0, .25);
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">Minha Empresa</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <input class="form-control form-control-dark w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search">
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="#">Sign out</a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3 sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">
                                <i class="bi bi-house-door-fill"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-file-earmark-text"></i>
                                Notas Fiscais
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-box"></i>
                                Produtos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-people"></i>
                                Clientes
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Notas Fiscais</h1>
                </div>

                <div class="container mt-3">
                    <a href="home.php" class="btn btn-primary mb-3">Voltar para Home</a>
                    <?php
                    require '../back/auth.php'; // Caminho para o arquivo auth.php
                    require_once '../back/relatoriocontroller.php';
                    
                    // Verifique se o usuário está autenticado
                    if (!isset($_SESSION['usuario_id'])) {
                        echo '<p>Você precisa estar logado para acessar esta página.</p>';
                        exit;
                    }
                    
                    $relatorio = new Relatorio($conn);
                    $notaSelecionada = isset($_GET['nota_id']) ? $_GET['nota_id'] : null;
                    $usuarioIdFiltro = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null;
                    
                    // Paginação
                    $itensPorPagina = 10;
                    $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $offset = ($paginaAtual - 1) * $itensPorPagina;
                    
                    try {
                        if ($notaSelecionada) {
                            $notaDetalhe = $relatorio->getNotaDetalhe($notaSelecionada);
                        } else {
                            if ($usuarioIdFiltro) {
                                // Consultar as notas filtradas pelo ID do usuário com paginação
                                $notas = $relatorio->getNotasPorUsuario($usuarioIdFiltro, $itensPorPagina, $offset);
                                $totalRegistros = $relatorio->contarNotasPorUsuario($usuarioIdFiltro);
                            } else {
                                // Consultar todas as notas com paginação
                                $notas = $relatorio->getTodasNotas($itensPorPagina, $offset);
                                $totalRegistros = $relatorio->contarTodasNotas();
                            }
                            
                            $totalPaginas = ceil($totalRegistros / $itensPorPagina);
                        }
                    } catch (PDOException $e) {
                        echo '<p>Erro ao acessar o banco de dados: ' . $e->getMessage() . '</p>';
                        exit;
                    }
                    ?>

                    <div class="mb-4">
                        <form class="form-inline" method="get" action="">
                            <div class="form-group mr-2">
                                <label for="usuario_id" class="mr-2">Filtrar por ID do Usuário:</label>
                                <input type="text" class="form-control" id="usuario_id" name="usuario_id" placeholder="ID do Usuário" value="<?php echo htmlspecialchars($usuarioIdFiltro); ?>">
                            </div>
                            <div class="form-group mr-2">
                                <label for="nota_id" class="mr-2">Filtrar por ID da Nota:</label>
                                <input type="text" class="form-control" id="nota_id" name="nota_id" placeholder="ID da Nota" value="<?php echo htmlspecialchars($notaSelecionada); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </form>
                    </div>

                    <?php if ($notaSelecionada && $notaDetalhe): ?>
                        <h2 class="mb-4">Detalhes da Nota Fiscal #<?php echo htmlspecialchars($notaSelecionada); ?></h2>
                        <ul id="detalhesNota" class="list-group">
                            <?php foreach ($notaDetalhe as $item): ?>
                                <li class="list-group-item" data-produto-id="<?php echo htmlspecialchars($item['produto_id']); ?>" data-quantidade="<?php echo htmlspecialchars($item['quantidade']); ?>" data-preco-unitario="<?php echo htmlspecialchars($item['preco_unitario']); ?>" data-subtotal="<?php echo htmlspecialchars($item['subtotal']); ?>" data-descricao="<?php echo htmlspecialchars($item['descricao']); ?>">
                                    <strong>Produto:</strong> <?php echo htmlspecialchars($item['descricao']); ?> - <strong>Quantidade:</strong> <?php echo htmlspecialchars($item['quantidade']); ?> - <strong>Preço Unitário:</strong> R$<?php echo htmlspecialchars($item['preco_unitario']); ?> - <strong>Subtotal:</strong> R$<?php echo htmlspecialchars($item['subtotal']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <form method="post" onsubmit="gerarPDF(); return false;" class="mt-3">
                            <input type="hidden" name="nota_id" value="<?php echo htmlspecialchars($notaSelecionada); ?>">
                            <button type="submit" class="btn btn-success">Exportar para PDF</button>
                        </form>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary mt-3">Voltar</a>
                    <?php else: ?>
                        <h2 class="mb-4">Notas Fiscais</h2>
                        <ul class="list-group">
                            <?php foreach ($notas as $nota): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Nota Fiscal #<?php echo htmlspecialchars($nota['id']); ?> - Usuário ID: <?php echo htmlspecialchars($nota['usuario_id']); ?> - Data: <?php echo htmlspecialchars($nota['data_venda']); ?> - Hora: <?php echo htmlspecialchars($nota['hora_venda']); ?> - Total: R$<?php echo htmlspecialchars($nota['total_venda']); ?>
                                    <a href="?nota_id=<?php echo htmlspecialchars($nota['id']); ?>" class="btn btn-info">Ver Detalhes</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if ($totalPaginas > 1): ?>
                            <nav aria-label="Navegação de página" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                        <li class="page-item <?php echo ($i == $paginaAtual) ? 'active' : ''; ?>">
                                            <a class="page-link" href="?pagina=<?php echo $i; ?><?php echo ($usuarioIdFiltro) ? '&usuario_id=' . htmlspecialchars($usuarioIdFiltro) : ''; ?><?php echo ($notaSelecionada) ? '&nota_id=' . htmlspecialchars($notaSelecionada) : ''; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function gerarPDF() {
            const doc = new jsPDF();
            let notaDetalhes = document.getElementById('detalhesNota');
            let content = '';
            if (notaDetalhes) {
                notaDetalhes.querySelectorAll('li').forEach(item => {
                    content += `${item.innerText}\n`;
                });
            }
            doc.text(content, 10, 10);
            doc.save('nota_fiscal.pdf');
        }
    </script>
</body>
</html>
