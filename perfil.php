<?php
    session_start();
    require_once("db/conexao.php");

    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id  = :usuario_id");

    $stmt->bindParam("usuario_id", $usuario_id);

    $stmt->execute();

    $usuario = $stmt->fetch();
    $data = new DateTime($usuario["created_at"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('templates/header.php'); ?>
    <main class="page">
        <div class="main-container">
            <div class="body-card">
                <h2>Meu Perfil</h2>
                <div class="perfil-info">
                    <p><strong>Nome:</strong></p>
                    <input type="text" value="<?= $usuario["nome"] ?>" disabled>
                </div>

                <div class="perfil-info">
                    <p><strong>Email:</strong></p>
                    <input type="text" value="<?= $usuario["email"] ?>" disabled>
                </div>
                <div class="perfil-info">
                    <p><strong>Data do Cadastro:</strong></p>
                    <input type="text" value="<?php echo $data->format('d/m/Y'); ?>" disabled>

                </div>
            </div>
        </div>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>