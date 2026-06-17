<?php
    session_start();
    require_once("db/conexao.php");

    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("
        SELECT * FROM estoque
        WHERE usuario_id = :usuario_id
    ");

    $stmt->bindParam(":usuario_id", $usuario_id);

    $stmt->execute();

    $estoques = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>
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
            <tr id="color-estoque">
                <th>Nome</th>
                <th>Marca</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>

            <?php foreach($estoques as $estoque): ?>
                <tr class="table-cor">
                    <td><?= $estoque["nome"] ?></td>
                    <td><?= $estoque["marca"] ?></td>
                    <td><?= $estoque["descricao"] ?></td>
                    <td><?= $estoque["quantidade"] ?></td>
                    <td><?= 'R$ ' . number_format($estoque["preco"], 2, ',', '.') ?></td>
                    <td>
                        <a class="btn editar" href="estoque/editar_estoque.php?id=<?= $estoque['id'] ?>">Editar</a>
                        <a class="btn excluir" href="estoque/excluir_estoque.php?id=<?= $estoque['id'] ?>">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>