<php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUa6mO3i7vafqONpsEX65BR5oTOZkSbr7TxKCkAo1uHBR9tTIBz0TsmrG5eD" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/index.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="login-form">
                <h4 class="text-center mb-4">Login</h4>

                <form method="POST" action="./back/usuariocontroller.php">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Digite seu email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha:</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Digite sua senha" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">Entrar</button>
                </form>
                <p class="text-center mt-3">Esqueceu sua senha? <a href="front/solicitar_recuperacao.php">Recuperar</a></p>
                <p class="text-center mt-2">Não tem uma conta? <a href="front/cadastra.php">Cadastre-se</a></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-QDtENHFI4UldDgt2zRbojoXfY62tD40egbwtTkKp4mCIcAKr/smrh94OlLL1rxdi" crossorigin="anonymous"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');

    if (error) {
        let message = '';
        if (error === 'invalid_credentials') {
            message = 'Email ou senha incorretos. Tente novamente.';
        } else if (error === 'no_funcao') {
            message = 'Sua função não está definida. Por favor, entre em contato com o administrador.';
        } else if (error === 'no_permission') {
            
            
            message = 'Você nao tem permissão para acessar esta página, seu acesso foi registrado no banco e sera encaminhado para o administrador do sistema a tentativa de acesso.';
        }
        
        else if (error === 'not_logged_in') {
            
            message = 'Você precisa estar logado para acessar esta página.';
        }

        if (message) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: message,
                confirmButtonText: 'OK'
            });
        }
    }
</script>

</body>
</html>
