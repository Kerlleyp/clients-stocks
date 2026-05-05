<?php

    require_once("../db/conexao.php");

   if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        header("Location: cliente.php");
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Clientes</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <h1 class="title">Sistema de Vendas</h1>
            <nav>
                <a href="../listar_clientes.php">Voltar</a>
            </nav>
        </div>
    </header>
    <div class="main-container">
        <div class="body-card">
            <h2>Editar Clientes !</h2>
            <form action="update_cliente.php" method="POST">
                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
                <input type="text" name="nome" value="<?= $cliente['nome'] ?>" required>
                <input type="text" name="telefone" value="<?= $cliente['telefone'] ?>">
                <input type="text" name="endereco" value="<?= $cliente['endereco'] ?>">
                <button type="submit">Atualizar</button>
            </form>
        </div>
    </div>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>