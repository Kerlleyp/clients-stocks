<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha</title>
</head>
<body>
    <h2>Recuperar senha</h2>

    <form action="enviar_codigo.php" method="POST">

        <label>E-mail</label>

        <input type="email" name="email">

        <button>Enviar código</button>

    </form>
</body>
</html>