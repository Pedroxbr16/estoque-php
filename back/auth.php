<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php?error=not_logged_in');
    exit();
}

// Defina permissões para cada página
$permissoes = [
    'home.php' => ['Estoque', 'Administrador'],
    'cadastro_estoque.php' => ['Estoque', 'Administrador'],  // Apenas funções "Estoque" e "Administrador" podem acessar
    'consulta_deposito.php' => ['Estoque', 'Venda', 'Administrador'], // "Estoque", "Venda" e "Administrador" podem acessar
    'relatorio.php' => ['Administrador','Estoque'],                   // Apenas "Administrador" pode acessar
    'editar_produto.php' => ['Estoque', 'Administrador'],    // Apenas "Estoque" e "Administrador" podem acessar
    'emissao_notas.php' => ['Administrador', 'Venda'],  
    'relatorionf.php' => ['Administrador', 'Venda'],
    'homeadm.php' => ['Administrador', ],
    'homeadmEV.php' => ['Administrador', ],

       
    // Adicione outras páginas e permissões conforme necessário
];

// Obtenha o nome do arquivo atual
$paginaAtual = basename($_SERVER['PHP_SELF']);

// Verifique se a página atual tem restrições de função
if (isset($permissoes[$paginaAtual]) && !in_array($_SESSION['usuario_funcao'], $permissoes[$paginaAtual])) {
    // Função do usuário não está permitida para acessar esta página
    header('Location: ../index.php?error=no_permission');
    session_unset();
    session_destroy();

    exit();
}


