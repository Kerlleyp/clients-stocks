<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("../conexao.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Deleta os itens da compra
    $stmt = $conn->prepare("DELETE FROM itens_compra WHERE compra_id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    // Deleta a compra
    $stmt = $conn->prepare("DELETE FROM compras WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
}

header("Location: ../lista_compras.php");
exit;