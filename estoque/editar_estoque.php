<?php

    require_once("../conexao.php");

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

<form action="update_estoque.php" method="POST">
    <input type="hidden" name="id" value="<?= $estoques['id'] ?>">
    <label for="nome">Nome: </label>
    <input type="text" name="nome" value="<?= $estoques['nome'] ?>" required>
    <label for="marca">Marca: </label>
    <input type="text" name="marca" value="<?= $estoques['marca'] ?>">
    <label for="quantidade">Quantidade: </label>
    <input type="text" name="qauntidade" value="<?= $estoques['quantidade'] ?>">
    <label for="preco">Preço: </label>
    <input type="text" name="preco" value="<?= $estoques['preco'] ?>">
    <label for="descricao">Descrição: </label>
    <textarea name="descricao"><?= $estoques['descricao'] ?></textarea>
    <button type="submit">Atualizar</button>
</form>