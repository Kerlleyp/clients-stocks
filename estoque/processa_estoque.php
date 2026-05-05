<?php
    require_once("../db/conexao.php");

    $nome = $_POST['nome'];
    $marca = $_POST['marca'];
    $descricao = $_POST['descricao'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];

    $stmt = $conn->prepare("INSERT INTO estoque (nome, descricao, quantidade, preco, marca) VALUES (:nome, :descricao, :quantidade, :preco, :marca)");

    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":descricao", $descricao);
    $stmt->bindParam(":quantidade", $quantidade);
    $stmt->bindParam(":preco", $preco);
    $stmt->bindParam(":marca", $marca);

    $stmt->execute();

    header("Location: ../estoque.php");
    exit;
?>