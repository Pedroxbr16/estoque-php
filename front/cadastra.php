<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no'>
    <title>Cadastrar</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/registro.css">
    <link rel="stylesheet" href="https://themes.getbootstrap.com/wp-content/themes/bootstrap-marketplace/style.css?ver=1590611604" />
</head>
<body class="page-template-default page page-id-7 page-parent woocommerce theme-dokan woocommerce-account woocommerce-page woocommerce-no-js dokan-theme-dokan">

<main id="main" class="site-main main">
    <section class="section method="POST">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card p-4">
                     
                        <h1 class="mb-4 text-center">Cadastre-se</h1>
                        <?php
                        // Verificar se há um erro e exibir a mensagem correspondente
                        if (isset($_GET['error']) && $_GET['error'] == 'email_exists') {
                            echo "<p class='text-danger text-center'>Erro: Este email já está em uso. Por favor, use outro email.</p>";
                        }
                        ?>

                        <form method="post" action="../back/usuariocontroller.php" class="register">
                            <div class="mb-3">
                                <label for="reg_sr_firstname" class="form-label">Primeiro nome <span class="required text-danger">*</span></label>
                                <input type="text" class="form-control" name="sr_firstname" id="reg_sr_firstname" value="" required/>
                            </div>

                            <div class="mb-3">
                                <label for="reg_sr_lastname" class="form-label">Sobrenome <span class="required text-danger">*</span></label>
                                <input type="text" class="form-control" name="sr_lastname" id="reg_sr_lastname" value="" required />
                            </div>

                            <div class="mb-3">
                                <label for="reg_sr_funcao" class="form-label">Função <span class="required text-danger">*</span></label>
                                <select class="form-select" name="sr_funcao" id="reg_sr_funcao" required>
                                    <option value="">Selecione uma função</option>
                                    <!-- As opções serão carregadas dinamicamente via JavaScript -->
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="reg_email" class="form-label">Email <span class="required text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="reg_email" value="" required/>
                            </div>

                            <div class="mb-3">
                                <label for="reg_password" class="form-label">Senha <span class="required text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" id="reg_password" required/>
                            </div>

                            <div class="text-center">
                                <input type="submit" class="btn btn-primary btn-lg w-100" name="register" value="Cadastrar" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Bootstrap JS and dependencies (optional, for better interactivity) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Carregar as funções disponíveis e preencher o campo select
        function carregarFuncoes() {
            $.ajax({
                url: '../back/usuariocontroller.php?action=listar_funcoes',
                type: 'GET',
                success: function (data) {
                    const funcoes = JSON.parse(data);
                    let opcoes = '<option value="" disabled selected>Selecione uma função</option>';
                    funcoes.forEach(funcao => {
                        opcoes += `<option value="${funcao.id}">${funcao.nome}</option>`;
                    });
                    $('#reg_sr_funcao').html(opcoes);
                },
                error: function () {
                    Swal.fire('Erro!', 'Erro ao carregar as funções.', 'error');
                }
            });
        }

        // Chamar a função para carregar as funções ao iniciar
        carregarFuncoes();
    });
</script>
</body>
</html>
