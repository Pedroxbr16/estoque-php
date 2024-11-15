<?php
session_start();
require '../back/auth.php'; // Caminho para o arquivo auth.php

include('../back/estoqueController.php');

$estoqueController = new EstoqueController();
$id = $_POST['id'];
$descricao = $_POST['descricao'];
$unidade_medida = $_POST['unidade_medida'];
$quantidade = $_POST['quantidade'];
$deposito = $_POST['deposito'];
$estoque_minimo = $_POST['estoque_minimo'];
$estoque_seguranca = $_POST['estoque_seguranca'];
$tipo_material = $_POST['tipo_material'];
$segmento = $_POST['segmento'];

$estoqueController->atualizarMaterial($id, $descricao, $unidade_medida, $quantidade, $deposito, $estoque_minimo, $estoque_seguranca, $tipo_material, $segmento);

// Redireciona de volta para a página de consulta
header("Location: consulta_deposito.php?status=updated");
exit;
?>
