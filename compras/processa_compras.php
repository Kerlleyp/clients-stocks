<?php
    require_once("../conexao.php");

    $cliente_id = $_POST['cliente_id'];
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    $stmt = $conn->prepare("SELECT * FROM estoque WHERE id = :produto_id");

    $stmt->bindParam(":produto_id", $produto_id);

    $stmt->execute();

    $produto = $stmt->fetch();

    if(!$produto) {

        echo "Produto não Existe !";
        exit;

    } else if($quantidade > $produto["quantidade"]) {
        echo "Estoque insuficiente";
        exit;
    }

    $preco_unitario = $produto["preco"];
    $subtotal = $quantidade * $produto["preco"];
    $total = $subtotal;

    $stmtCompra  = $conn->prepare("INSERT INTO compras (cliente_id, total) VALUES (:cliente_id, :total)");

    $stmtCompra->bindParam(":cliente_id", $cliente_id);
    $stmtCompra->bindParam(":total", $total);

    $stmtCompra->execute();

    $compra_id = $conn->lastInsertId();

    $stmtItem = $conn->prepare("INSERT INTO itens_compra (compra_id, produto_id, quantidade, preco_unitario, subtotal) VALUES (:compra_id, :produto_id, :quantidade, :preco_unitario, :subtotal)");

    $stmtItem->bindParam(":compra_id", $compra_id);
    $stmtItem->bindParam(":produto_id", $produto_id);
    $stmtItem->bindParam(":quantidade", $quantidade);
    $stmtItem->bindParam(":preco_unitario", $preco_unitario);
    $stmtItem->bindParam(":subtotal", $subtotal);

    $stmtItem->execute();

    $novo_estoque = $produto["quantidade"] - $quantidade;

    $stmtUpdate = $conn->prepare("UPDATE estoque SET quantidade = :quantidade WHERE id = :produto_id");

    $stmtUpdate->bindParam(":quantidade", $novo_estoque);
    $stmtUpdate->bindParam(":produto_id", $produto_id);

    $stmtUpdate->execute();

    header("Location: ../compras.php");
    exit;
?>