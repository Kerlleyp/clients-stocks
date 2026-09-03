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
    <?php require_once('templates/header.php') ?>
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
    <div class="main-container">
        <div class="perfil-card">
            <div class="card-header">
                <div class="card-icone">📦</div>
                <div>
                    <h2>Registrar Produtos !</h2>
                    <p>Preencha os dados do cliente para realizar o cadastro no sistema.</p>
                </div>
            </div>

            <form action="estoque/processa_estoque.php" method="POST">
                <div class="form-grid">
                    <div class="form-grupo">
                        <label for="nome">Nome do Produto:</label>
                        <input type="text" name="nome" placeholder="Produto">
                    </div>
                    <div class="form-grupo">
                        <label for="marca">Marca:</label>
                        <input type="text" name="marca" placeholder="Marca">
                    </div>
                    <div class="form-grupo">
                        <label for="quantidade">Quantidade:</label>
                        <input type="number" name="quantidade" placeholder="Quantidade">
                    </div>
                    <div class="form-grupo">
                        <label for="preco">Preço:</label>
                        <input type="number" name="preco" step="0.01" placeholder="preço">
                    </div>
                </div>
                <div class="form-grupo">
                    <label for="descricao">Descrição:</label>
                    <textarea name="descricao" rows="4" cols="63" placeholder="Descrição"></textarea>
                </div>
                <div class="acoes-form">
                    <a href="listar_estoque.php" class="btn-secundario">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Estoque
                    </a>
                    <button type="submit" class="btn-fixo">
                        <i class="fa-solid fa-cart-plus"></i>
                        Registrar Produto
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php require_once('templates/footer.php'); ?>
</body>

</html>