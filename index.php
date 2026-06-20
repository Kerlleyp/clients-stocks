<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque & Cliente</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <h1 class="title">🛒 Sistema de Vendas</h1>
        </div>
    </header>
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
        <div class="main-container" >
            <div class="body-card">
                <h2>Bem-vindo!</h2>
                <p class="login-texto">
                    Faça login para acessar o sistema.
                </p>
                <form action="usuarios/processa_login.php" method="POST">
                    <input type="email" name="email" id="email" placeholder="📧 Email" required>
                    <input type="password" name="password" id="password" placeholder="🔒 Senha" required>
                    <button type="submit">🚪 Logar</button>
                </form>
                <p class="separador">--------------------- ou ---------------------</p>
                <a href="cadastrar.php" class="btn-cadastro">
                    Criar Conta
                </a>
            </div>
        </div>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>