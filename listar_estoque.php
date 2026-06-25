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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
    <main  class="page-list">
        <h2 class="title-list">📦Estoque</h2>
        <p class="separador">Gerencie os produtos cadastrados no sistema.</p>
        <div class="list-card">
            <div class="topo-tabela">
                <div class="topo-esquerda">
                    <a href="estoque.php" class="btn-novo">
                        <i class="fa-solid fa-plus"></i> Novo Produto
                    </a>
                </div>

                <div class="topo-direita">
                    <input type="text" placeholder="Buscar Produto...">
                </div>
            </div>
            <!--Mostra os Clientes-->
            <table class="table-container">
                <tr id="color-estoque">
                   <th><i class="fa-solid fa-box"></i> Nome</th>
                    <th><i class="fa-solid fa-building"></i> Marca</th>
                    <th><i class="fa-solid fa-file-lines"></i> Descrição</th>
                    <th><i class="fa-solid fa-cubes"></i> Quantidade</th>
                    <th><i class="fa-solid fa-money-bill-wave"></i> Preço</th>
                    <th><i class="fa-solid fa-gear"></i> Ações</th>
                </tr>

                <?php foreach($estoques as $estoque): ?>
                <tr class="table-cor">
                    <td><?= $estoque["nome"] ?></td>
                    <td><?= $estoque["marca"] ?></td>
                    <td><?= $estoque["descricao"] ?></td>
                    <td><?= $estoque["quantidade"] ?></td>
                    <td><?= 'R$ ' . number_format($estoque["preco"], 2, ',', '.') ?></td>
                    <td>
                        <a class="btn editar" href="estoque/editar_estoque.php?id=<?= $estoque['id'] ?>"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                        <a class="btn excluir" href="estoque/excluir_estoque.php?id=<?= $estoque['id'] ?>"><i class="fa-solid fa-trash"></i> Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </table>
        </div>
    </main>
           
    <?php require_once('templates/footer.php'); ?>
</body>
</html>