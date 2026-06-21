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
            header("Location: alterar_senha.php");
            exit;
        }
        
        if ($novaSenha !== $confirmSenha) {
            $_SESSION['error'] = "As novas senhas não conferem!";
            header("Location: alterar_senha.php");
            exit;
        }

        $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE usuarios SET senha= :senha WHERE id = :usuario_id");
        $stmt->bindParam(":senha", $novaSenhaHash);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();

        $_SESSION['success'] = "Senha alterada com sucesso!";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar senha</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php require_once('../templates/header.php'); ?>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="success">
            <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="error">
            <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <main class="page">
        <div class="main-container">
            <div class="body-card">
                <h2>Alterar senha</h2>
                <form action="" method="POST">
                    <input type="password" name="senha_atual" id="senha_atual" placeholder="Senha Atual">
                    <input type="password" name="nova_senha" id="nova_senha" placeholder="Nova Senha">
                    <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Confirmar Senha">
                    <button type="submit">Atualizar</button>
                </form>
            </div>
        </div>
    </main>
    <?php require_once('../templates/footer.php'); ?>
</body>
</html>