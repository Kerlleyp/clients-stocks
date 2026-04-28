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
    <div class="main-container">
        <div class="body-card">
            <h2>Registrar Produtos !</h2>
            <form action="estoque/processa_estoque.php" method="POST">
                <input type="text" name="nome" placeholder="Produto" required>
                <input type="text" name="marca" placeholder="Marca" required>
                <input type="text" name="quantidade" placeholder="Quantidade">
                <input type="number" name="preco" placeholder="preço">
                <textarea name="descricao" rows="4" cols="63"></textarea>
                <button type="submit">Cadastrar</button>
            </form>
        </div>
    </div>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>