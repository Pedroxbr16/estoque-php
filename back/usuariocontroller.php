<?php
require_once 'db.php';

session_start();

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
                    // Email já cadastrado - Redirecionar para a página de cadastro com mensagem de erro
                    header('Location: ../front/cadastra.php?error=email_exists');
                    exit();
                }

                // Preparar a consulta SQL para inserir dados
                $sql = "INSERT INTO usuarios (nome, sobrenome, funcao, email, password) VALUES (:firstname, :lastname, :funcao, :email, :password)";
                $stmt = $conn->prepare($sql);

                // Associar valores aos parâmetros
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':funcao', $funcao);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);

                // Executar a declaração
                $stmt->execute();

                // Redirecionar para index.php após o cadastro
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
                // Preparar a consulta para buscar o usuário pelo email
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
    
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
                // Verificar se o usuário existe e a senha está correta
                if ($usuario && password_verify($password, $usuario['password'])) {
                    // Login bem-sucedido: salvar dados do usuário na sessão
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['firstname'];
                    $_SESSION['usuario_funcao'] = $usuario['funcao']; // Adiciona função à sessão
    
                    // Verificar se a função está definida
                    if (empty($usuario['funcao'])) {
                        // Função não definida - Redirecionar com mensagem de aviso
                        header('Location: ../estoque-php/index.php?error=no_funcao');
                        exit();
                    }
    
                    // Redirecionar para a página baseada na função do usuário
                    if ($usuario['funcao'] == 'Venda') {
                        header('Location: ../front/home_vendas.php');
                    } elseif ($usuario['funcao'] == 'Estoque') {
                        header('Location: ../front/home_estoque.php');
                    } else {
                        // Caso a função não seja "Venda" ou "Estoque", redirecionar para uma página padrão
                        header('Location: ../front/home_default.php');
                    }
                    exit();
                } else {
                    // Credenciais inválidas - Redirecionar de volta ao login com uma mensagem de erro
                    header('Location: ../estoque-php/index.php?error=invalid_credentials');
                    exit();
                }
            } catch (PDOException $e) {
                echo "Erro ao validar login: " . $e->getMessage();
            }
        } else {
            echo "Erro ao conectar ao banco de dados.";
        }
    }
    

}

// Verificar se o formulário foi enviado para cadastro ou login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = new Usuario();

    if (isset($_POST['register'])) {
        // Cadastro de usuário
        $firstname = $_POST['sr_firstname'];
        $lastname = $_POST['sr_lastname'];
        $funcao = $_POST['sr_funcao']; // Novo campo para função
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $usuario->cadastrarUsuario($firstname, $lastname, $funcao, $email, $password);
    } elseif (isset($_POST['login'])) {
        // Login de usuário
        $email = $_POST['email'];
        $password = $_POST['password'];

        $usuario->validarLogin($email, $password);
    }
}


?>
