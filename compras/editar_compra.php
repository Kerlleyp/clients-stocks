<?php

session_start();
require_once("../db/conexao.php");

if (isset($_POST['item_compra_id'])) {

    $novaQuantidade = $_POST['novaQuantidade'];
    $item_compra_id = $_POST['item_compra_id'];

    $stmt = $conn->prepare('SELECT * FROM itens_compra WHERE id = :item_compra_id');

    $stmt->bindParam(':item_compra_id', $item_compra_id);
    $stmt->execute();


    $compra = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($compra) {
        try {
            $conn->beginTransaction();

            $diferenca = $compra['quantidade'] - $novaQuantidade;
            $produto_id = $compra['produto_id'];

            $stmtUpdateEstoque = $conn->prepare('UPDATE estoque SET quantidade = quantidade +  :diferenca WHERE id = :produto_id');
            $stmtUpdateEstoque->bindParam(':diferenca', $diferenca);
            $stmtUpdateEstoque->bindParam(':produto_id', $produto_id);
            $stmtUpdateEstoque->execute();

            $stmtUpdateCompra = $conn->prepare('UPDATE itens_compra SET quantidade = :novaQuantidade WHERE id = :item_compra_id');
            $stmtUpdateCompra->bindParam(':novaQuantidade', $novaQuantidade);
            $stmtUpdateCompra->bindParam(':item_compra_id', $item_compra_id);
            $stmtUpdateCompra->execute();

            $conn->commit();

            $_SESSION['success'] = "Atualização com sucesso!";
        } catch (Exception $e) {

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            $_SESSION['error'] = "EROR!";
        }
    }
}

header("Location: ../lista_compras.php#item-" . $item_compra_id);
exit;