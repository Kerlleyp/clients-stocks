<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes & Estoques</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('templates/header.php'); ?>

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
    <main>
        <div class="main-container">
            <div class="body-card">
                <h2>Registrar Clientes !</h2>
                <form action="./clientes/processa_cliente.php" method="POST">
                    <input type="text" name="nome" placeholder="Nome">
                    <input type="text" name="telefone" placeholder="Telefone">
                    <input type="text" name="endereco" placeholder="Endereço">
                    <button type="submit">Cadastrar</button>
                </form>
            </div>
        </div>
    </main>
   <?php require_once('templates/footer.php'); ?>
</body>
</html>