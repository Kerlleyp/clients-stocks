<?php

session_start();
require_once("../db/conexao.php");
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['item_compra_id'])) {

    $novaQuantidade = $_POST['novaQuantidade'];
    $item_compra_id = $_POST['item_compra_id'];

    if ($novaQuantidade <= 0) {
        $_SESSION['error'] = "Quantidade inválida!";
        header("Location: ../lista_compras.php");
        exit;
    }

    $stmt = $conn->prepare('
        SELECT * 
        FROM itens_compra 
        WHERE id = :item_compra_id
    ');

    $stmt->bindParam(':item_compra_id', $item_compra_id);
    $stmt->execute();

    $compra = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($compra) {

        try {


            // Calcula diferença do estoque

            $diferenca = $compra['quantidade'] - $novaQuantidade;

            $produto_id = $compra['produto_id'];

            if($compra['quantidade'] < $novaQuantidade) {

                $diferencaValidacao = $novaQuantidade - $compra['quantidade'];

                $stmtEstoque = $conn->prepare('SELECT * FROM estoque WHERE id = :produto_id');
                $stmtEstoque->bindParam(':produto_id', $produto_id);
                $stmtEstoque->execute();

                $estoque = $stmtEstoque->fetch(PDO::FETCH_ASSOC);

                if($diferencaValidacao > $estoque['quantidade']) {

                    $_SESSION['error'] = "Erro estoque insuficiente!";
                    header("Location: ../lista_compras.php#item-" . $item_compra_id);
                    exit;
                }
            }

            $conn->beginTransaction();

            // Atualiza estoque

            $stmtUpdateEstoque = $conn->prepare('
                UPDATE estoque
                SET quantidade = quantidade + :diferenca
                WHERE id = :produto_id
            ');

            $stmtUpdateEstoque->bindParam(':diferenca', $diferenca);
            $stmtUpdateEstoque->bindParam(':produto_id', $produto_id);
            $stmtUpdateEstoque->execute();


            // Calcula o novo SubTotal
            $novoSubTotal = $novaQuantidade * $compra['preco_unitario'];

            // Atualiza quantidade do item

            $stmtUpdateItem = $conn->prepare('
                UPDATE itens_compra
                SET quantidade = :novaQuantidade,
                subtotal = :novoSubTotal
                WHERE id = :item_compra_id
            ');

            $stmtUpdateItem->bindParam(':novaQuantidade', $novaQuantidade);
            $stmtUpdateItem->bindParam(':novoSubTotal', $novoSubTotal);
            $stmtUpdateItem->bindParam(':item_compra_id', $item_compra_id);
            $stmtUpdateItem->execute();



            // Recalcula total da compra

            $stmtTotal = $conn->prepare('
                SELECT SUM(quantidade * preco_unitario) AS total
                FROM itens_compra
                WHERE compra_id = :compra_id
            ');

            $stmtTotal->bindParam(':compra_id', $compra['compra_id']);
            $stmtTotal->execute();

            $novoTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);



            // Atualiza tabela compras

            $stmtUpdateCompra = $conn->prepare('
                UPDATE compras
                SET total = :total
                WHERE id = :compra_id
            ');

            $stmtUpdateCompra->bindParam(':total', $novoTotal['total']);
            $stmtUpdateCompra->bindParam(':compra_id', $compra['compra_id']);
            $stmtUpdateCompra->execute();



            $conn->commit();

            $_SESSION['success'] = "Atualização com sucesso!";


        } catch (Exception $e) {

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            $_SESSION['error'] = "Erro ao atualizar compra!";
        }

    }

}


header("Location: ../lista_compras.php#item-" . $item_compra_id);
exit;