<?php

    require_once("db/conexao.php");

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
    <title>Compras</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('templates/header.php'); ?>
    <main>
        <div class="main-container">
            <div class="body-card">
                <h2>Registrar Compras</h2>
                <form action="./compras/processa_compras.php" method="POST">
                    <label for="cliente_id">Informe o nome do cliente:</label><br>
                    <select name="cliente_id" id="cliente_id"><br>
                        <?php foreach($clientes as $cliente): ?>
                            <option value="<?= $cliente['id'] ?>"><?= $cliente['nome'] ?></option>
                        <?php endforeach; ?>
                    </select><br>
                    <label for="produto_id">Informe o Produto:</label><br>
                    <select name="produto_id" id="produto_id"><br>
                        <?php foreach($estoques as $estoque): ?>
                            <option value="<?= $estoque['id'] ?>"><?= $estoque['nome'] . ' - ' . 'R$ ' . $estoque['preco'] . ' - ' . 'Estoque: ' . $estoque['quantidade'] ?></option>
                        <?php endforeach; ?>
                    </select><br>
                    <label for="quantidade">Quantidade da compra: </label>
                    <input type="number" name="quantidade" id="quantidade"><br>
                    <button type="submit">Compras</button>
                </form>
            </div>
        </div>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>