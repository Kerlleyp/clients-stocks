<?php
    session_start();
    require_once("../db/conexao.php");

    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id  = :usuario_id");
    $stmt->bindParam("usuario_id", $usuario_id);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $nome = $_POST["nome"];
        $email = $_POST["email"];

        $stmt = $conn->prepare("UPDATE usuarios SET nome = :nome, email = :email WHERE id = :usuario_id");
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();

        $_SESSION['success'] = "Perfil alterada com sucesso!";

        header("Location: ../perfil.php");
        exit;
    }
?>