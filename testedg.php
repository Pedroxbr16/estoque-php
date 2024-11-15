<?php
session_start();

if (!isset($_SESSION['teste'])) {
    $_SESSION['teste'] = "Sessão funcionando!";
    echo "Sessão iniciada. Recarregue a página para verificar a persistência.";
} else {
    echo "Sessão persistente: " . $_SESSION['teste'];
}
?>
