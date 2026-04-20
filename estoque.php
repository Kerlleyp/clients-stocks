<?php

    require_once("conexao.php");

    $stmt = $conn->query("SELECT * FROM estoque");

    $estoques = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
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
    <br>
    <!--Mostra os Clientes-->
    <table border="1">
        <tr>
            <td>Nome</td>
            <td>Marca</td>
            <td>Descrição</td>
            <td>Quantidade</td>
            <td>Preço</td>
            <td>Ações</td>
        </tr>

        <?php foreach($estoques as $estoque): ?>
            <tr>
                <td><?= $estoque["nome"] ?></td>
                <td><?= $estoque["marca"] ?></td>
                <td><?= $estoque["descricao"] ?></td>
                <td><?= $estoque["quantidade"] ?></td>
                <td><?= $estoque["preco"] ?></td>
                <td>
                    <a href="estoque/editar_estoque.php?id=<?= $estoque['id'] ?>">Editar</a> |
                    <a href="estoque/excluir_estoque.php?id=<?= $estoque['id'] ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>