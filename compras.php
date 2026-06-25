<?php
    session_start();
    require_once("db/conexao.php");

    $usuario_id = $_SESSION["usuario_id"];

    $stmtEstoque = $conn->prepare("SELECT * FROM estoque WHERE usuario_id = :usuario_id");
    $stmtClientes = $conn->prepare("SELECT * FROM clientes WHERE usuario_id = :usuario_id");

    $stmtEstoque->execute([':usuario_id' => $usuario_id]);
    $stmtClientes->execute([':usuario_id' => $usuario_id]);

    $estoques = $stmtEstoque->fetchAll(PDO::FETCH_ASSOC);
    $clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <?php require_once('templates/header.php'); ?>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="success">
            <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="error">
            <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <main class="page">
        <div class="main-container">
            <div class="perfil-card compras-card">

                <!-- TOPO DO CARD -->
                <div class="card-header compras-header">
                    <div class="card-icone">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>

                    <div class="card-text">
                        <h2>Registrar Compras</h2>
                        <p>Preencha os dados abaixo para registrar a compra do cliente no sistema.</p>
                    </div>
                </div>

                <!-- FORM -->
                <form action="./compras/processa_compras.php" method="POST" class="form-compras">
                    
                    <div class="form-grid">

                        <!-- CLIENTE -->
                        <div class="form-grupo">
                            <label for="cliente_id">Cliente</label>

                            <div class="select-wrapper">
                                <select name="cliente_id" id="cliente_id" required>
                                    <option value="" selected disabled>Selecione um cliente</option>

                                    <?php foreach($clientes as $cliente): ?>
                                        <option value="<?= $cliente['id'] ?>">
                                            <?= htmlspecialchars($cliente['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- PRODUTO -->
                        <div class="form-grupo">
                            <label for="produto_id">Produto</label>

                            <div class="select-wrapper">
                                <select name="produto_id" id="produto_id" required>
                                    <option value="" selected disabled>Selecione um produto</option>

                                    <?php foreach($estoques as $estoque): ?>
                                        <option value="<?= $estoque['id'] ?>">
                                            <?= htmlspecialchars($estoque['nome']) ?> • 
                                            R$ <?= number_format($estoque['preco'], 2, ',', '.') ?> • 
                                            Estoque: <?= $estoque['quantidade'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- QUANTIDADE -->
                    <div class="form-grupo">
                        <label for="quantidade">Quantidade da compra</label>
                        <input 
                            type="number" 
                            name="quantidade" 
                            id="quantidade" 
                            min="1" 
                            placeholder="Digite a quantidade"
                            required
                        >
                    </div>

                    <!-- BOX EXTRA -->
                    <div class="box-info-compra">
                        <div class="info-item">
                            <span class="info-label">Observação</span>
                            <strong>Verifique o estoque antes de finalizar a compra.</strong>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Dica</span>
                            <strong>Selecione o cliente e o produto corretamente antes de registrar.</strong>
                        </div>
                    </div>

                    <!-- AÇÕES -->
                    <div class="acoes-form">
                        <a href="lista_compras.php" class="btn-secundario">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            Histórico
                        </a>

                        <button type="submit" class="btn-fixo">
                            <i class="fa-solid fa-cart-plus"></i>
                            Registrar compra
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php require_once('templates/footer.php'); ?>
</body>
</html>