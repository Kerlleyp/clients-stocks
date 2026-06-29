<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque & Cliente</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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
        <div class="main-container">
            <div class="body-card">
                <div class="login-icon">
                    <i class="fa-solid fa-user"></i>
                </div>
                <h2>Bem-vindo!</h2>
                <p class="login-texto">
                    Faça login para acessar o sistema.
                </p>
                <form action="usuarios/processa_login.php" method="POST">
                    <div class="input-group">
                        <i class="fa-solid fa-envelope"></i>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            placeholder="Digite seu e-mail"
                            required>
                    </div>
                    <div class="input-group senha-group">
                        <i class="fa-solid fa-lock"></i>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Digite sua senha"
                            required>
                        <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                    <div class="login-options">
                        <label>
                            <input type="checkbox">
                            Lembrar de mim
                        </label>
                        <a href="perfil/esqueci_senha.php">
                            Esqueci minha senha
                        </a>
                    </div>
                    <button type="submit">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Entrar
                    </button>
                </form>
                <div class="separador">
                    <span>ou</span>
                </div>
                <a href="cadastrar.php" class="btn-cadastro">
                    <i class="fa-solid fa-user-plus"></i>
                    Criar Conta
                </a>
            </div>
        </div>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>