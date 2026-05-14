<?php
    session_start();
    require_once("../db/conexao.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];

        $stmt = $conn->prepare("UPDATE clientes 
            SET nome = :nome, telefone = :telefone, endereco = :endereco 
            WHERE id = :id");

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":telefone", $telefone);
        $stmt->bindParam(":endereco", $endereco);

         if($stmt->execute()) {
            $_SESSION['success'] = "Cliente Editado com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao Editar cliente!";
        }

        header("Location: ../listar_clientes.php");
        exit;
    }
?>