<?php

require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se todos os campos obrigatórios estão preenchidos
    if (
        isset($_POST['sr_firstname']) &&
        isset($_POST['sr_lastname']) &&
        isset($_POST['email']) &&
        isset($_POST['password'])
    ) {
        // Obter os valores dos campos do formulário
        $firstname = $_POST['sr_firstname'];
        $lastname = $_POST['sr_lastname'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Criptografar a senha

        try {
            // Obter a conexão ao banco de dados
            $conn = getConnection();

            // Preparar a consulta SQL para inserir os dados no banco de dados
            $sql = "INSERT INTO usuarios (nome, sobrenome, email, password) VALUES (:firstname, :lastname, :email, :password)";
            $stmt = $conn->prepare($sql);

            // Associar os valores aos parâmetros da consulta
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);

            // Executar a consulta
            if ($stmt->execute()) {
                echo "Cadastro realizado com sucesso!";
            } else {
                echo "Erro ao realizar o cadastro.";
            }
        } catch (PDOException $e) {
            // Se houver erro na execução, exibe uma mensagem
            echo 'Erro: ' . $e->getMessage();
        }
    } else {
        echo "Por favor, preencha todos os campos obrigatórios.";
    }
}


?>
