<?php

    require_once("../conexao.php");

   if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        header("Location: clientes.php");
        exit;
    }

?>

<form action="update_cliente.php" method="POST">
    <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

    <input type="text" name="nome" value="<?= $cliente['nome'] ?>" required>
    <input type="text" name="telefone" value="<?= $cliente['telefone'] ?>">
    <input type="text" name="endereco" value="<?= $cliente['endereco'] ?>">

    <button type="submit">Atualizar</button>
</form>