
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes & Estoques</title>
</head>
<body>
    <form action="./clientes/processa_cliente.php" method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="telefone" placeholder="Telefone">
        <input type="text" name="endereco" placeholder="Endereço">
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>