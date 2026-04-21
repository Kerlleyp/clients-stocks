<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes & Estoques</title>
</head>
<body>
    <form action="estoque/processa_estoque.php" method="POST">
        <input type="text" name="nome" placeholder="Produto" required>
        <input type="text" name="marca" placeholder="Marca" required>
        <input type="text" name="quantidade" placeholder="Quantidade">
        <input type="number" name="preco" placeholder="preço">
        <textarea name="descricao"></textarea>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>