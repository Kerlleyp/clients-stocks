<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes & Estoques</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('templates/header.php') ?>
    <main>
        <div class="main-container">
            <div class="body-card">
                <h2>Registrar Clientes !</h2>
                <form action="./clientes/processa_cliente.php" method="POST">
                    <input type="text" name="nome" placeholder="Nome" required>
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