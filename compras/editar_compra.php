<?php

session_start();
require_once("../db/conexao.php");

if(isset($_GET['id'])){

    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM compras WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    $compra = $stmt->fetch();

    echo "<pre>";
    print_r($compra);
    echo "</pre>";

}

$conn->beginTransaction();

// Buscar itens antigos da compra
$stmtItens = $conn->prepare("SELECT * FROM itens_compra WHERE compra_id = :id");
$stmtItens->bindParam(":id", $id);
$stmtItens->execute();

$itens = $stmtItens->fetchAll();

foreach ($itens as $item) {

    $produto = $item['produto_id'];
    $quantidade = $item['quantidade'];

    $stmtEstoque = $conn->prepare("
        UPDATE estoque 
        SET quantidade = quantidade + :quantidade 
        WHERE id = :produto_id
    ");

    $stmtEstoque->bindValue(":quantidade", $quantidade);
    $stmtEstoque->bindValue(":produto_id", $produto);
    $stmtEstoque->execute();
}
