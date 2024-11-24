<?php

session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas Fiscais</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
    <button class="back-button">Voltar para Home</button>        <?php
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
        <?php
        // Configuração para exibir apenas 10 páginas por vez
        $maxPaginasVisiveis = 10;
        $inicio = max(1, $paginaAtual - floor($maxPaginasVisiveis / 2));
        $fim = min($totalPaginas, $inicio + $maxPaginasVisiveis - 1);

        // Ajusta início se o fim estiver no limite
        if ($fim - $inicio < $maxPaginasVisiveis - 1) {
            $inicio = max(1, $fim - $maxPaginasVisiveis + 1);
        }
        ?>

        <!-- Botão Anterior -->
        <li class="page-item <?php echo ($paginaAtual <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?pagina=<?php echo max(1, $paginaAtual - 1); ?>&usuario_id=<?php echo htmlspecialchars($usuarioIdFiltro); ?>" aria-label="Anterior">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <!-- Botão para ir ao início (caso existam muitas páginas anteriores) -->
        <?php if ($inicio > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?pagina=1&usuario_id=<?php echo htmlspecialchars($usuarioIdFiltro); ?>">1...</a>
            </li>
        <?php endif; ?>

        <!-- Números das páginas -->
        <?php for ($i = $inicio; $i <= $fim; $i++): ?>
            <li class="page-item <?php echo ($i == $paginaAtual) ? 'active' : ''; ?>">
                <a class="page-link" href="?pagina=<?php echo $i; ?>&usuario_id=<?php echo htmlspecialchars($usuarioIdFiltro); ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Botão para ir ao fim (caso existam muitas páginas seguintes) -->
        <?php if ($fim < $totalPaginas): ?>
            <li class="page-item">
                <a class="page-link" href="?pagina=<?php echo $totalPaginas; ?>&usuario_id=<?php echo htmlspecialchars($usuarioIdFiltro); ?>">...<?php echo $totalPaginas; ?></a>
            </li>
        <?php endif; ?>

        <!-- Botão Próximo -->
        <li class="page-item <?php echo ($paginaAtual >= $totalPaginas) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?pagina=<?php echo min($totalPaginas, $paginaAtual + 1); ?>&usuario_id=<?php echo htmlspecialchars($usuarioIdFiltro); ?>" aria-label="Próxima">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>


            <?php endif; ?>
        <?php endif; ?>
    </div>
    <script>
        window.jsPDF = window.jspdf.jsPDF;

        function gerarPDF() {
            const doc = new jsPDF();

            doc.setFontSize(16);
            doc.text("Nota Fiscal", 105, 10, { align: "center" });
            doc.setFontSize(12);
            doc.text("Produto", 10, 30);
            doc.text("Quantidade", 80, 30);
            doc.text("Preço Unitário", 120, 30);
            doc.text("Subtotal", 170, 30);

            let posY = 40;
            let total = 0;

            const produtos = document.querySelectorAll('#detalhesNota li');
            produtos.forEach((produto) => {
                const descricao = produto.getAttribute('data-descricao');
                const quantidade = produto.getAttribute('data-quantidade');
                const precoUnitario = produto.getAttribute('data-preco-unitario');
                const subtotal = produto.getAttribute('data-subtotal');

                doc.text(descricao, 10, posY);
                doc.text(String(quantidade), 80, posY);
                doc.text("R$ " + parseFloat(precoUnitario).toFixed(2), 120, posY);
                doc.text("R$ " + parseFloat(subtotal).toFixed(2), 170, posY);
                total += parseFloat(subtotal);
                posY += 10;
            });

            doc.setFontSize(12);
            doc.text("Total: R$ " + total.toFixed(2), 170, posY + 10);

            doc.save("nota_fiscal.pdf");
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let homeUrl = '<?php echo $_SESSION['homeUrl'] ?? ""; ?>';
</script>
<script src="../assets/js/voltar_home.js"></script>

</body>
</html>