<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque & Cliente</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1 class="title">Sistema de Vendas</h1>
    </header>
    <div class="paragrafo">
        <p>Bem-Vindo ao sistema de gerenciamento de vendas e Clientes !</p>
    </div>
    <div class="card-container">
        <div class="cards">
            <img src="img/clientes.png" alt="Clientes">
            <h2 class="sub-title">Clientes</h2>
           <div class="btn-container">
                <a href="cliente.php" class="btn-card" id="customers">Cadastrar Clientes</a>
                <a href="listar_clientes.php" class="btn-card" id="customers">Listar Clientes</a>
           </div>
        </div>
        <div class="cards">
            <img src="img/produto.png" alt="Produtos">
            <h2 class="sub-title">Produtos</h2>
            <div class="btn-container">
                <a href="estoque.php" class="btn-card" id="products">Cadastrar Produto</a>
                <a href="listar_estoque.php" class="btn-card" id="products">Listar Produto</a>
            </div>
        </div>
        <div class="cards">
            <img src="img/compras.png" alt="Compras">
            <h2 class="sub-title">Compras</h2>
            <div class="btn-container">
                <a href="compras.php" class="btn-card" id="shopping">Nova Compra</a>
                <a href="lista_compras.php" class="btn-card" id="shopping">Historico de Compras</a>
            </div>
        </div>
        <div class="cards">
            <img src="img/relatorios.png" alt="Relatorios">
            <h2 class="sub-title">Relatórios</h2>
           <div class="btn-container">
                <a href="cadastrar_cliente.php" class="btn-card" id="report">Estoque Baixo</a>
                <a href="cadastrar_cliente.php" class="btn-card" id="report">Produtos mais Vendidos</a>
           </div>
        </div>
    </div>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>