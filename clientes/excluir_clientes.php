<?php

    require_once("../conexao.php");

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM clientes WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }

    header("Location: ../cliente.php");
    exit;