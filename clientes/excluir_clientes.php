<?php
    session_start();
    require_once("../db/conexao.php");

    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM clientes WHERE id = :id");
        $stmt->bindParam(":id", $id);

        if($stmt->execute()) {
            $_SESSION['success'] = "Cliente excluído com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao excluir cliente!";
        }

    } else {
        $_SESSION['error'] = "Cliente não encontrado!";
    }

    header("Location: ../listar_clientes.php");
    exit;
?>