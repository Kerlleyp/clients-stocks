<?php
    session_start();
    require_once("../db/conexao.php");

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $cliente_id = $_POST['cliente_id'] ?? null;
        $produto_id = $_POST['produto_id'] ?? null;
        $quantidade = $_POST['quantidade'] ?? null;

        if(empty($cliente_id)){
            $_SESSION['error'] = "CLIENTE NÃO INFORMADO";
            header("Location: ../compras.php");
            exit;
        }

        if(empty($produto_id)){
            $_SESSION['error'] = "PRODUTO NÃO INFORMADO";
            header("Location: ../compras.php");
            exit;
        }

        if($quantidade <= 0){
            $_SESSION['error'] = "QUANTIDADE INVÁLIDA";
            header("Location: ../compras.php");
            exit;
        }

        try {

            $stmt = $conn->prepare("SELECT * FROM estoque WHERE id = :produto_id");

            $stmt->bindParam(":produto_id", $produto_id);

            $stmt->execute();

            $produto = $stmt->fetch();

            if(!$produto) {

                $_SESSION['error'] = "Produto não existe!";
                header("Location: ../compras.php");
                exit;

            } else if($quantidade > $produto["quantidade"]) {

                $_SESSION['error'] = "Estoque insuficiente!";
                header("Location: ../compras.php");
                exit;
            }

            $preco_unitario = $produto["preco"];
            $subtotal = $quantidade * $produto["preco"];
            $total = $subtotal;

            $conn->beginTransaction();

            $stmtCompra  = $conn->prepare("INSERT INTO compras (cliente_id, total) VALUES (:cliente_id, :total)");

            $stmtCompra->bindParam(":cliente_id", $cliente_id);
            $stmtCompra->bindParam(":total", $total);

            $stmtCompra->execute();

            $compra_id = $conn->lastInsertId();

            $stmtItem = $conn->prepare("INSERT INTO itens_compra 
            (compra_id, produto_id, quantidade, preco_unitario, subtotal) 
            VALUES (:compra_id, :produto_id, :quantidade, :preco_unitario, :subtotal)");

            $stmtItem->bindParam(":compra_id", $compra_id);
            $stmtItem->bindParam(":produto_id", $produto_id);
            $stmtItem->bindParam(":quantidade", $quantidade);
            $stmtItem->bindParam(":preco_unitario", $preco_unitario);
            $stmtItem->bindParam(":subtotal", $subtotal);

            $stmtItem->execute();

            $novo_estoque = $produto["quantidade"] - $quantidade;

            $stmtUpdate = $conn->prepare("UPDATE estoque 
            SET quantidade = :quantidade 
            WHERE id = :produto_id");

            $stmtUpdate->bindParam(":quantidade", $novo_estoque);
            $stmtUpdate->bindParam(":produto_id", $produto_id);

            $stmtUpdate->execute();

            $conn->commit();

            $_SESSION['success'] = "Compra realizada com sucesso!";

            header("Location: ../compras.php");
            exit;

        } catch(Exception $e) {

            $conn->rollBack();

            $_SESSION['error'] = "Erro ao realizar compra!";

            header("Location: ../compras.php");
            exit;
        }

    } else {

        $_SESSION['error'] = "INFORMAÇÕES INVÁLIDAS";

        header("Location: ../compras.php");
        exit;
    }
?>