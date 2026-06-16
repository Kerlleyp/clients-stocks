<?php
    session_start();
    require_once("db/conexao.php");

    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("
        SELECT * FROM clientes
        WHERE usuario_id = :usuario_id
    ");

    $stmt->bindParam(":usuario_id", $usuario_id);

    $stmt->execute();

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
    <?php if(isset($_SESSION['success'])): ?>
        <div class="success">
            <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="error">
            <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>
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