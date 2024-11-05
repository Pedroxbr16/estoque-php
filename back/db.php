<?php

// Função para retornar a conexão ao banco de dados
function getConnection() {
    $servername = "localhost";
    $username = "root";
    $password = ""; // Geralmente no XAMPP a senha é vazia para root
    $dbname = "clinica"; // Nome do banco de dados


    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn; // Retorna a conexão
    } catch (PDOException $e) {
        echo "Erro de conexão: " . $e->getMessage();
        return null; // Retorna null caso a conexão falhe
    }
}
$conn = getConnection();
if (!$conn) {
    die("Erro ao conectar ao banco de dados");
}


?>
