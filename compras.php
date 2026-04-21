<?php

    require_once("conexao.php");

    $stmtEstoque = $conn->query("SELECT * FROM estoque");

    $stmtClientes = $conn->query("SELECT * FROM clientes");

    $estoques = $stmtEstoque->fetchAll(PDO::FETCH_ASSOC);

    $clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="./compras/processa_compras.php" method="POST">
        <label for="cliente_id">Informe o nome do cliente</label>
        <select name="cliente_id" id="cliente_id">
            <?php foreach($clientes as $cliente): ?>
                <option value="<?= $cliente['id'] ?>"><?= $cliente['nome'] ?></option>
            <?php endforeach; ?>
        </select>
        <label for="produto_id">Informe o Produto</label>
        <select name="produto_id" id="produto_id">
            <?php foreach($estoques as $estoque): ?>
                <option value="<?= $estoque['id'] ?>"><?= $estoque['nome'] . ' - ' . 'R$ ' . $estoque['preco'] . ' - ' . 'Estoque: ' . $estoque['quantidade'] ?></option>
            <?php endforeach; ?>
        </select>
        <label for="quantidade">Quantidade da compra: </label>
        <input type="number" name="quantidade" id="quantidade">
        <button type="submit">Compras</button>
    </form>
</body>
</html>