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
    <main>
        <div class="main-container">
            <div class="body-card">
                <h2>Cadastrar Usuario !</h2>
                <form action="usuarios/processa_usuarios.php" method="POST">
                    <input type="text" name="nome" placeholder="Nome" required>
                    <input type="email" name="email" id="email" placeholder="Email" required>
                    <input type="password" name="password" id="password" placeholder="Senha" required>
                    <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirmar Senha" required>
                    <button type="submit">Cadastrar</button>
                </form>
            </div>
        </div>
    </main>
   <?php require_once('templates/footer.php'); ?>
</body>
</html>