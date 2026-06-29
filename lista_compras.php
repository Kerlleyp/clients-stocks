<?php
    session_start();
    require_once("db/conexao.php");

    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("SELECT
        compras.id AS compra_id,
        clientes.nome AS cliente_nome,
        estoque.nome AS produto_nome,
        estoque.preco,
        SUM(itens_compra.quantidade) AS quantidade_total
        FROM compras
        JOIN clientes ON compras.cliente_id = clientes.id
        JOIN itens_compra ON itens_compra.compra_id = compras.id
        JOIN estoque ON itens_compra.produto_id = estoque.id
        WHERE estoque.usuario_id = :usuario_id
        GROUP BY compras.id, clientes.nome, estoque.nome, estoque.preco
        ORDER BY clientes.nome
    ");

    $stmt->execute([
        ':usuario_id' => $usuario_id
    ]);

    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $clienteAtual = null;
    $totalDaCompra = 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras por Cliente</title>
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

    <main class="page-list">
        <h2 class="title-list">🛒Compras</h2>
        <p class="separador">Gerencie suas compras cadastrados no sistema</p>
        
        <div class="list-card">
            <div class="topo-tabela">
                <div class="topo-esquerda">
                    <a href="compras.php" class="btn-novo">
                        <i class="fa-solid fa-plus"></i> Cadastrar Compras
                    </a>
                </div>

                <div class="topo-direita">
                    <input type="text" placeholder="Buscar cliente...">
                </div>
            </div> 

            <?php 
                $clienteAtual = null;
                $totalDaCompra = 0;
            ?>

            <?php foreach($compras as $compra): ?>
                <?php if($clienteAtual != $compra['cliente_nome']): ?>

                    <?php if($clienteAtual !== null): ?>
                        </tbody>
                        </table>
                        <p class="total-cliente">
                            Total do cliente: R$ <?= number_format($totalDaCompra, 2, ',', '.') ?>
                        </p>
                        <div class="separador">
                            <span><i class="fa-solid fa-receipt"></i></span>
                        </div>
                    <?php endif; ?>

                    <h2 class="cliente-title"><?= $compra['cliente_nome'] ?></h2>
                    
                    <table class="table-container">
                        <thead>
                            <tr id="color-compras">
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Total</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php 
                        $clienteAtual = $compra['cliente_nome'];
                        $totalDaCompra = 0;
                    ?>
                <?php endif; ?>

                <?php 
                    $total_produto = (float)$compra['preco'] * (int)$compra['quantidade_total']; 
                    $totalDaCompra += $total_produto;
                ?>
                
                <tr>
                    <td><?= $compra['produto_nome'] ?></td>
                    <td><?= $compra['quantidade_total'] ?></td>
                    <td>R$ <?= number_format($total_produto, 2, ',', '.') ?></td>
                    <td>
                        <a class="btn excluir" href="compras/excluir_compra.php?id=<?= $compra['compra_id'] ?>">
                            <i class="fa-solid fa-trash"></i> Excluir
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if($clienteAtual !== null): ?>
                </tbody>
                </table>
                <p class="total-cliente">
                    Total do cliente: R$ <?= number_format($totalDaCompra, 2, ',', '.') ?>
                </p>
                <div class="separador">
                    <span><i class="fa-solid fa-receipt"></i></span>
                </div>
            <?php endif; ?>
            
        </div>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>
