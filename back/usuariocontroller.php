<?php
session_start();
require_once 'db.php';

class Usuario {

    // Função para cadastrar um usuário
    public function cadastrarUsuario($firstname, $lastname, $funcao, $email, $password) {
        $conn = getConnection();
        if ($conn) {
            try {
                // Verificar se o email já está cadastrado
                $sqlCheck = "SELECT * FROM usuarios WHERE email = :email";
                $stmtCheck = $conn->prepare($sqlCheck);
                $stmtCheck->bindParam(':email', $email);
                $stmtCheck->execute();

                if ($stmtCheck->rowCount() > 0) {
                    header('Location: ../front/cadastra.php?error=email_exists');
                    exit();
                }

                // Inserir dados do usuário
                $sql = "INSERT INTO usuarios (nome, sobrenome, funcao, email, password) VALUES (:firstname, :lastname, :funcao, :email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':funcao', $funcao);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->execute();

                header('Location: ../index.php');
                exit();
            } catch (PDOException $e) {
                echo "Erro ao inserir dados: " . $e->getMessage();
            }
        } else {
            echo "Erro ao conectar ao banco de dados.";
        }
    }

    // Função para validar o login
    public function validarLogin($email, $password) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $conn = getConnection();
        if ($conn) {
            try {
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario && password_verify($password, $usuario['password'])) {
                    // Ajuste os campos conforme os nomes no banco de dados
                    $_SESSION['usuario_id'] = $usuario['id_usuario'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    $_SESSION['usuario_funcao'] = $usuario['funcao'];
                    $_SESSION['usuarioId'] = $usuario['id_usuario']; // Define o ID do usuário na sessão
                    
                    // Definir URL da home com base na função do usuário
                    if ($usuario['funcao'] == 'Venda') {
                        $_SESSION['homeUrl'] = '../front/home_vendas.php';
                        header('Location: ../front/home_vendas.php');
                    } elseif ($usuario['funcao'] == 'Estoque') {
                        $_SESSION['homeUrl'] = '../front/home.php';
                        header('Location: ../front/home.php');
                    } elseif ($usuario['funcao'] == 'Administrador') {
                        $_SESSION['homeUrl'] = '../front/homeadmEV.php';
                        header('Location: ../front/homeadmEV.php');
                    } else {
                        header('Location: ../index.php?error=no_funcao');
                    }
                    exit();
                } else {
                    header('Location: ../index.php?error=invalid_credentials');
                    exit();
                }
            } catch (PDOException $e) {
                echo "Erro ao validar login: " . $e->getMessage();
            }
        } else {
            echo "Erro ao conectar ao banco de dados.";
        }
    }

    // Função para deslogar o usuário
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ../index.php');
        exit();
    }
}

// Verificar se o formulário foi enviado para cadastro, login ou logout
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = new Usuario();

    if (isset($_POST['register'])) {
        $firstname = $_POST['sr_firstname'];
        $lastname = $_POST['sr_lastname'];
        $funcao = $_POST['sr_funcao'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $usuario->cadastrarUsuario($firstname, $lastname, $funcao, $email, $password);
    } elseif (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $usuario->validarLogin($email, $password);
    }
}

// Verificar se o logout foi requisitado
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $usuario = new Usuario();
    $usuario->logout();
}
?>
