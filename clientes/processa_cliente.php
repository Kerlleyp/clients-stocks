<?php
    require_once("../db/conexao.php");

    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    $stmt = $conn->prepare("INSERT INTO clientes (nome, telefone, endereco) VALUES (:nome, :telefone, :endereco)");

    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":telefone", $telefone);
    $stmt->bindParam(":endereco", $endereco);

    $stmt->execute();

    header("Location: ../cliente.php");
    exit;
?>