<?php

require_once '../back/db.php';

$conn = getConnection();

// Buscar notificações não visualizadas
$stmt = $conn->prepare("SELECT * FROM notificacoes_estoque WHERE visualizado = FALSE ORDER BY data_notificacao DESC");
$stmt->execute();
$notificacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificações de Estoque</title>
    <link rel="stylesheet" href="../assets/css/painel_estoque.css">
</head>

<body>
    <!-- notificacoes.html - HTML da Página de Notificações -->
    <div class="notificacoes-container">
        <h2>Notificações de Estoque</h2>
        <table id="tabela-notificacoes">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Mensagem</th>
                    <th>Data da Notificação</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notificacoes as $notificacao): ?>
                    <tr>
                        <td><?= htmlspecialchars($notificacao['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($notificacao['produto_id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($notificacao['mensagem'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($notificacao['data_notificacao'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <button onclick="abrirModal(<?= htmlspecialchars(json_encode($notificacao), ENT_QUOTES, 'UTF-8') ?>)">Visualizar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para Notificações -->
    <div id="modalNotificacao" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharModal()">&times;</span>
            <h3 id="modalTitulo">Detalhes da Notificação</h3>
            <p id="modalMensagem"></p>
            <button onclick="enviarEmailSupervisor()">Enviar Email para Supervisor</button>
            <button onclick="fecharModal()">Cancelar</button>
        </div>
    </div>

    <script src="../assets/js/painel_estoque.js"></script>
</body>

</html>
