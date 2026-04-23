<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes & Estoques</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <h1 class="title">Sistema de Vendas</h1>
            <nav>
                <a href="index.php">Home</a>
                <div class="menu">
                    <span>Cliente</span>
                    <div class="sub_links">
                        <a href="cliente.php">➕ Adicionar Clientes</a>
                        <a href="listar_clientes.php">📋 Lista dos Clientes</a>
                    </div>
                </div>
                <div class="menu">
                    <span>Produto</span>
                    <div class="sub_links">
                        <a href="estoque.php">➕ Adicionar Produtos</a>
                        <a href="listar_estoque.php">📋 Lista dos Produtos</a>
                    </div>
                </div>
                <a href="relatorios.php">Relatórios</a>
            </nav>
        </div>
    </header>
    <div class="register-container">
        <div class="register">
            <h2>Registrar Produtos !</h2>
            <form action="estoque/processa_estoque.php" method="POST">
                <input type="text" name="nome" placeholder="Produto" required>
                <input type="text" name="marca" placeholder="Marca" required>
                <input type="text" name="quantidade" placeholder="Quantidade">
                <input type="number" name="preco" placeholder="preço">
                <textarea name="descricao" rows="4" cols="63"></textarea>
                <button type="submit">Cadastrar</button>
            </form>
        </div>
    </div>
</body>
</html>