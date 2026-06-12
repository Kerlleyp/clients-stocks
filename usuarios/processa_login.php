<?php
session_start();
require_once("../db/conexao.php");
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verifica se os campos estão vazios
    if(empty($email) || empty($password)) {

        $_SESSION['error'] = "Preencha todos os campos!";
        header("Location: ../index.php");
        exit;

    }

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");

    $stmt->bindParam(":email", $email);

    $resultado = $stmt->execute();

    $usuario = $stmt->fetch();

    if($usuario === false){
        $_SESSION['error'] = "Email não cadastrado !";
        header("Location: ../index.php");
        exit;
    }

    if(password_verify($password, $usuario['senha'])) {

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        header("Location: ../dashboard.php");
        exit;

    } else {

        $_SESSION['error'] = "Senha inválida!";
        header("Location: ../index.php");
        exit;

    }

}else {
    $_SESSION['error'] = "Requisição inválida!";
    header("Location: ../index.php");
    exit;
}