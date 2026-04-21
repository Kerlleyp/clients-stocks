<?php
    require_once("conexao.php");

    $stmt = $conn->query("SELECT * FROM clientes");

    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
</head>
<body>
     <!--Mostra os Clientes-->
    <table border="1">
        <tr>
            <td>Nome</td>
            <td>Telefone</td>
            <td>Endereço</td>
            <td>Ações</td>
        </tr>

        <?php foreach($clientes as $cliente): ?>
            <tr>
                <td><?= $cliente["nome"] ?></td>
                <td><?= $cliente["telefone"] ?></td>
                <td><?= $cliente["endereco"] ?></td>
                <td><a href="clientes/editar_cliente.php?id=<?= $cliente['id'] ?>">Editar</a></td>
                <td><a href="clientes/excluir_clientes.php?id=<?= $cliente['id'] ?>">Excluir</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>