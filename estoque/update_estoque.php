<?php
    session_start();
    require_once("../db/conexao.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $marca = $_POST['marca'];
        $quantidade = $_POST['quantidade'];
        $preco = $_POST['preco'];
        $descricao = $_POST['descricao'];

        $stmt = $conn->prepare("UPDATE estoque 
            SET nome = :nome, descricao = :descricao, quantidade = :quantidade , preco = :preco, marca = :marca
            WHERE id = :id");

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":marca", $marca);
        $stmt->bindParam(":quantidade", $quantidade);
        $stmt->bindParam(":preco", $preco);
        $stmt->bindParam(":descricao", $descricao);

         if($stmt->execute()) {
            $_SESSION['success'] = "Produto Editado com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao Editar Produto!";
        }

        header("Location: ../listar_estoque.php");
        exit;
    }
?>