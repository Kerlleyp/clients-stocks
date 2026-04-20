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
    <title>Document</title>
</head>
<body>

    <?php foreach($clientes as $cliente): ?>
        <h1><?= $cliente['nome'] ?></h1>
    <?php endforeach; ?>
</body>
</html>