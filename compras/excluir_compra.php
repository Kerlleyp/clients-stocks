<?php
session_start();
require_once("../db/conexao.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    //Pegar Cliente 
    $stmt = $conn->prepare("SELECT * FROM compras WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $compra = $stmt->fetch();
    if ($compra) {
        try {
            $conn->beginTransaction();

            $cliente_id = $compra['cliente_id'];
            $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = :cliente_id");
            $stmt->bindParam(":cliente_id", $cliente_id);
            $stmt->execute();
            $cliente = $stmt->fetch();
            if($cliente){
                $nome_cliente = $cliente['nome'];
            } else {
                $nome_cliente = "Cliente";
            }

            $stmtItens = $conn->prepare("SELECT * FROM itens_compra WHERE compra_id = :id");
            $stmtItens->bindParam(":id", $id);
            $stmtItens->execute();
            $itens = $stmtItens->fetchAll();
            foreach ($itens as $item) {
                $produto = $item['produto_id'];
                $quantidade = $item['quantidade'];

                $stmtEstoque = $conn->prepare("UPDATE estoque SET quantidade = quantidade + :quantidade WHERE id = :produto_id");
                $stmtEstoque->bindValue(":quantidade", $quantidade);
                $stmtEstoque->bindValue(":produto_id", $produto);
                $stmtEstoque->execute();
            }


            // Deleta os itens da compra
            $stmt = $conn->prepare("DELETE FROM itens_compra WHERE compra_id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            // Deleta a compra
            $stmt = $conn->prepare("DELETE FROM compras WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            $conn->commit();

            $_SESSION['success'] = "Compra do(a) $nome_cliente excluída com sucesso!";
            
        } catch (Exception $e) {

            if($conn->inTransaction()){
                $conn->rollBack();
            }
            $_SESSION['error'] = "Erro ao excluir compra!";
        }
    } else {
        $_SESSION['error'] = "Compra não encontrada!";
    }
}

header("Location: ../lista_compras.php");
exit;
