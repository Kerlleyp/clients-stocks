<?php
    require_once("../conexao.php");

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $cliente_id = $_POST['cliente_id'] ?? null;
        $produto_id = $_POST['produto_id'] ?? null;
        $quantidade = $_POST['quantidade'] ?? null;

        if(empty($cliente_id)){

            echo "Error: CLIENTE NÃO INFORMADO";
            exit;
        }

        if(empty($produto_id)){

            echo "Error: PRODUTO NÃO INFORMADO";
            exit;
        }

        if($quantidade <= 0){

            echo "Error: QUANTIDADE INVALIDA";
            exit;
        }

       try {

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

           $conn->beginTransaction();

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

            $conn->commit();

            header("Location: ../compras.php");
            exit;
       } catch(Exception $e) {
            $conn->rollBack();

            echo $e->getMessage();
       }
    } else {

        echo " Error: INFORMAÇÕES IVÁLIDAS";
    }
    
?>