<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php?error=not_logged_in');
    exit();
}

// Defina permissões para cada página
$permissoes = [
    'home.php' => ['Estoque', 'Admin'],
    'cadastro_estoque.php' => ['Estoque', 'Admin'],  // Apenas funções "Estoque" e "Admin" podem acessar
    'consulta_deposito.php' => ['Estoque', 'Venda', 'Admin'], // "Estoque", "Venda" e "Admin" podem acessar
    'relatorio.php' => ['Admin','Estoque'],                   // Apenas "Admin" pode acessar
    'editar_produto.php' => ['Estoque', 'Admin'],    // Apenas "Estoque" e "Admin" podem acessar
    'emissao_notas.php' => ['Admin', 'Venda'],  
    'relatorionf.php.php' => ['Admin', 'Venda'],
       // Apenas "Admin" e "Venda" podem acessar
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


