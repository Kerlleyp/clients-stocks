<?php
    session_start();
    require_once("../db/conexao.php");

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];

        $stmt = $conn->prepare("INSERT INTO clientes (nome, telefone, endereco) VALUES (:nome, :telefone, :endereco)");

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":telefone", $telefone);
        $stmt->bindParam(":endereco", $endereco);

        $resultado = $stmt->execute();

        if($resultado === true) {
            $_SESSION['success'] = "Cliente cadastrado com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao cadastrar cliente!";
        }

    } else {
        $_SESSION['error'] = "Erro ao cadastrar cliente!";
    }

    header("Location: ../cliente.php");
    exit;
?>