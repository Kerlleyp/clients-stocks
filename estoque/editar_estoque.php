<?php

    require_once("../db/conexao.php");

   if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("SELECT * FROM estoque WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $estoques = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        header("Location: estoque.php");
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <h1 class="title">Sistema de Vendas</h1>
            <nav>
                <a href="../listar_estoque.php">Voltar</a>
            </nav>
        </div>
    </header>
    <div class="main-container">
        <div class="body-card">
            <h2>Editar Produto !</h2>
            <form action="update_estoque.php" method="POST">
                <input type="hidden" name="id" value="<?= $estoques['id'] ?>">
                <label for="nome">Nome: </label>
                <input type="text" name="nome" value="<?= $estoques['nome'] ?>" required>
                <label for="marca">Marca: </label>
                <input type="text" name="marca" value="<?= $estoques['marca'] ?>">
                <label for="quantidade">Quantidade: </label>
                <input type="text" name="quantidade" value="<?= $estoques['quantidade'] ?>">
                <label for="preco">Preço: </label>
                <input type="text" name="preco" value="<?= $estoques['preco'] ?>">
                <label for="descricao">Descrição: </label>
                <textarea name="descricao" rows="4" cols="63"><?= $estoques['descricao'] ?></textarea>
                <button type="submit">Atualizar</button>
            </form>
        </div>
    </div>
    <?php require_once('../templates/footer.php'); ?>
</body>
</html>