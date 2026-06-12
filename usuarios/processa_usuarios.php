<?php
session_start();
require_once("../db/conexao.php");
if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        // Verifica se os campos estão vazios
        if(empty($nome) || empty($email) || empty($password) || empty($confirmPassword)) {

            $_SESSION['error'] = "Preencha todos os campos!";
            header("Location: ../cadastrar.php");
            exit;

        } else if($password != $confirmPassword) {

            $_SESSION['error'] = "Senha Incorretas!";
            header("Location: ../cadastrar.php");
            exit;
        }

        try {
                $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");

                $stmt->bindParam(":email", $email);

                $resultado = $stmt->execute();

                $usuario = $stmt->fetch();

                if($usuario !== false) {

                    $_SESSION['error'] = "Email já cadastrado!";
                    header("Location: ../cadastrar.php");
                    exit;
                }

                $senhaHash = password_hash($password, PASSWORD_DEFAULT);

                $stmtUser  = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");

                $stmtUser->bindParam(":nome", $nome);
                $stmtUser->bindParam(":email", $email);
                $stmtUser->bindParam(":senha", $senhaHash);

                $confirmUser = $stmtUser->execute();

                if($confirmUser) {
                    $_SESSION['success'] = "Usuario cadastrado com sucesso!";
                    header("Location: ../index.php");
                    exit;
                } else {
                    $_SESSION['error'] = "Erro ao cadastrar Usuario!";
                    header("Location: ../index.php");
                    exit;
                }


            } catch(PDOException $e) {

                $_SESSION['error'] = "Erro no banco: " . $e->getMessage();
                header("Location: ../cadastrar.php");
                exit;

            }


} else {

    $_SESSION['error'] = "Requisição inválida!";
    header("Location: ../cadastrar.php");
    exit;

}