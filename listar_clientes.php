<?php
    require_once("db/conexao.php");

    $stmt = $conn->query("SELECT * FROM clientes");

    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('templates/header.php') ?>
    <main>
         <!--Mostra os Clientes-->
        <table class="table-container">
            <tr id="color-clientes">
                <th>Nome</th>
                <th>Telefone</th>
                <th>Endereço</th>
                <th>Ações</th>
            </tr>

            <?php foreach($clientes as $cliente): ?>
                <tr class="table-cor">
                    <td><?= $cliente["nome"] ?></td>
                    <td><?= $cliente["telefone"] ?></td>
                    <td><?= $cliente["endereco"] ?></td>
                    <td>
                        <a class="btn editar" href="clientes/editar_cliente.php?id=<?= $cliente['id'] ?>">Editar</a>
                        <a class="btn excluir" href="clientes/excluir_clientes.php?id=<?= $cliente['id'] ?>">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>