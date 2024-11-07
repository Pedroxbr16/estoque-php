<?php
// Função para retornar a conexão ao banco de dados
function getConnection() {
    $servername = "localhost";
    $username = "pjusto";
    $password = "1234"; // Atualize com a senha correta se necessário
    $dbname = "estoque";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn; // Retorna a conexão
    } catch (PDOException $e) {
        echo "Erro de conexão: " . $e->getMessage();
        return null; // Retorna null se a conexão falhar
    }
}
?>
