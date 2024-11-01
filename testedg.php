<?php
// arquivo test_db_connection.php

// Incluindo o arquivo db.php para usar a função de conexão ao banco de dados
require './back/db.php';

// Tentar conectar ao banco de dados
try {
    $conn = getConnection();
    echo "Conexão com o banco bem-sucedida!";
} catch (PDOException $e) {
    echo 'Erro de conexão: ' . $e->getMessage();
}
?>
