<?php
// Configurações do banco de dados
$host = '127.0.0.1';       // Nome do servidor de banco de dados
$dbname = 'clinica';  // Nome do banco de dados
$username = 'root';      // Nome de usuário do banco de dados
$password = '';        // Senha do banco de dados

// Função para retornar a conexão ao banco de dados
function getConnection() {
    global $host, $dbname, $username, $password;

    try {
        // Conectar ao banco de dados usando PDO
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

        // Definir o modo de erro para exceções
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    } catch (PDOException $e) {
        // Se houver erro na conexão, exibe uma mensagem
        echo 'Erro de conexão: ' . $e->getMessage();
        exit;
    }
}
?>
