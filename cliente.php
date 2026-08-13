<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes & Estoques</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php require_once('templates/header.php'); ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>
    <main class="page">
        <div class="main-container">
            <div class="perfil-card">
                <div class="card-header">
                    <div class="card-icone">👥</div>
                    <div>
                        <h2>Registrar Clientes</h2>
                        <p>Preencha os dados do cliente para realizar o cadastro no sistema.</p>
                    </div>
                </div>

                <form action="./clientes/processa_cliente.php" method="POST">
                    <div class="form-grid">
                        <div class="form-grupo">
                            <label for="nome">Nome:</label>
                            <input type="text" name="nome" placeholder="Nome">
                        </div>

                        <div class="form-grupo">
                            <label for="telefone">Telefone:</label>
                            <input type="text" name="telefone" placeholder="Telefone">
                        </div>
                    </div>

                    <div class="form-grupo">
                        <label for="endereco">Endereço:</label>
                        <input type="text" name="endereco" placeholder="Endereço">
                    </div>

                    <div class="acoes-form">
                        <a href="listar_clientes.php" class="btn-secundario">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            Clientes
                        </a>

                        <button type="submit" class="btn-fixo">
                            <i class="fa-solid fa-cart-plus"></i>
                            Registrar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>

</html>