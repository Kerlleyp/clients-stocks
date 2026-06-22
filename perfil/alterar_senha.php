<?php
    session_start();
    require_once("../db/conexao.php");

    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id  = :usuario_id");
    $stmt->bindParam("usuario_id", $usuario_id);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $senhaAtual = $_POST["senha_atual"];
        $novaSenha = $_POST["nova_senha"];
        $confirmSenha = $_POST["confirmar_senha"];

        if (!password_verify($senhaAtual, $usuario["senha"])) {
            $_SESSION['error'] = "Senha atual incorreta!";
            header("Location: ../perfil.php");
            exit;
        }
        
        if ($novaSenha !== $confirmSenha) {
            $_SESSION['error'] = "As novas senhas não conferem!";
            header("Location: ../perfil.php");
            exit;
        }

        $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE usuarios SET senha= :senha WHERE id = :usuario_id");
        $stmt->bindParam(":senha", $novaSenhaHash);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();

        $_SESSION['success'] = "Senha alterada com sucesso!";

        header("Location: ../perfil.php");
        exit;
    }
?>