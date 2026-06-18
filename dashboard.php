<?php
    session_start();
    require_once("db/conexao.php");

    if(!isset($_SESSION['usuario_id'])) {
        header("Location: index.php");
        exit;
    }
    
    $usuario_id = $_SESSION['usuario_id'];

    //Total Clientes 
    $stmt = $conn->prepare(" SELECT COUNT(*) AS total FROM clientes WHERE usuario_id = ? ");

    $stmt->bindParam(1, $usuario_id);

    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    $total_clientes = $resultado['total'] ?? 0;

    //Total Produtos 
    $stmt = $conn->prepare(" SELECT COUNT(*) AS total FROM estoque WHERE usuario_id = ? ");

    $stmt->bindParam(1, $usuario_id);

    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    $total_produtos = $resultado['total'] ?? 0;

    //Total compras 
    $stmt = $conn->prepare(" SELECT COUNT(*) AS total FROM compras WHERE usuario_id = ? ");

    $stmt->bindParam(1, $usuario_id);

    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    $total_compras = $resultado['total'] ?? 0;

    //Total de itens no estoque 
    $stmt = $conn->prepare(" SELECT SUM(quantidade) AS total FROM estoque WHERE usuario_id = ? ");

    $stmt->bindParam(1, $usuario_id);

    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    $total_estoque = $resultado['total'] ?? 0;


?>
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
        <div class="nav-container">

            <div class="user-area">
                <span class="user-name">
                    <?= $_SESSION['usuario_nome'] ?>
                </span>
            </div>

            <h1 class="title">🛒 Sistema de Vendas e Cadastro</h1>

            <a href="logout.php" class="btn-sair-header">
                Sair
            </a>

        </div>
    </header>
   <div class="paragrafo">
        <h2>Olá, <span class="usuario"><?= $_SESSION['usuario_nome'] ?></span> 👋</h2>
    </div>

    <!--DASHBOARD-->
    <div class="dashboard">
        <div class="card-dashboard">
            <h2><?= $total_clientes ?></h2>
            <p>Clientes</p>
        </div>

        <div class="card-dashboard">
            <h2><?= $total_produtos ?></h2>
            <p>Produtos</p>
        </div>

        <div class="card-dashboard">
            <h2><?= $total_compras ?></h2>
            <p>Compras</p>
        </div>

        <div class="card-dashboard">
            <h2><?= $total_estoque ?></h2>
            <p>Itens em Estoque</p>
        </div>
    </div>
    <div class="card-container">
        <div class="cards" id="card-cliente">
            <img src="img/clientes.png" alt="Clientes">
            <h2 class="sub-title">Clientes</h2>
           <div class="btn-container">
                <a href="cliente.php" class="btn-card" id="customers">Cadastrar Clientes</a>
                <a href="listar_clientes.php" class="btn-card" id="customers">Listar Clientes</a>
           </div>
        </div>
        <div class="cards" id="card-produto">
            <img src="img/produto.png" alt="Produtos">
            <h2 class="sub-title">Produtos</h2>
            <div class="btn-container">
                <a href="estoque.php" class="btn-card" id="products">Cadastrar Produto</a>
                <a href="listar_estoque.php" class="btn-card" id="products">Listar Produto</a>
            </div>
        </div>
        <div class="cards" id="card-compras">
            <img src="img/compras.png" alt="Compras">
            <h2 class="sub-title">Compras</h2>
            <div class="btn-container">
                <a href="compras.php" class="btn-card" id="shopping">Nova Compra</a>
                <a href="lista_compras.php" class="btn-card" id="shopping">Historico de Compras</a>
            </div>
        </div>
        <div class="cards" id="card-relatorio">
            <img src="img/relatorios.png" alt="Relatorios">
            <h2 class="sub-title">Relatórios</h2>
            <div class="btn-container">
                <a href="relatorio_estoque_baixo.php" class="btn-card" id="report">Estoque Baixo</a>
                <a href="relatorio_vendidos.php" class="btn-card" id="report">Produtos mais Vendidos</a>
            </div>
        </div>
    </div>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>