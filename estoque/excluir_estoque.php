<?php
    session_start();
    require_once("../db/conexao.php");

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM estoque WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        if($stmt->execute()) {
            $_SESSION['success'] = "Produto excluído com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao excluir Produto!";
        }

    } else {
        $_SESSION['error'] = "Produto não encontrado!";
    }

    header("Location: ../listar_estoque.php");
    exit;
