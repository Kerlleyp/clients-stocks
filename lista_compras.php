<?php

    require_once("conexao.php");

    $stmt = $conn->query("SELECT
        clientes.nome AS cliente_nome,
        estoque.nome AS produto_nome,
        estoque.preco,
        SUM(itens_compra.quantidade) AS quantidade_total
        FROM compras
        JOIN clientes ON compras.cliente_id = clientes.id
        JOIN itens_compra ON itens_compra.compra_id = compras.id
        JOIN estoque ON itens_compra.produto_id = estoque.id
        GROUP BY clientes.nome, estoque.nome, estoque.preco
        ORDER BY clientes.nome
    ");

    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $clienteAtual = null;
    $totalDaCompra = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras por Cliente</title>
</head>
<body>
    <?php foreach($compras as $compra): ?>

        <?php if($clienteAtual != $compra['cliente_nome']): ?>

            <?php if($clienteAtual !== null): ?>
                <p><strong>Total do cliente: R$ <?= number_format($totalDaCompra, 2, ',', '.') ?></strong></p>
                </ul>
            <?php endif; ?>

            <h1><?= $compra['cliente_nome'] ?></h1>
            <ul>
                <?php 
                    $clienteAtual = $compra['cliente_nome'];
                    $totalDaCompra = 0;
                ?>
        <?php endif; ?>

        <?php $total_produto = (float)$compra['preco'] * (int)$compra['quantidade_total']; ?>
        <?php $totalDaCompra += $total_produto; ?>

        <li>
            <?= $compra['produto_nome'] ?> - 
            <?= $compra['quantidade_total'] ?> - 
            R$ <?= number_format($total_produto, 2, ',', '.') ?>
        </li>

    <?php endforeach; ?>

    <?php if($clienteAtual !== null): ?>
        <p><strong>Total do cliente: R$ <?= number_format($totalDaCompra, 2, ',', '.') ?></strong></p>
        </ul>
    <?php endif; ?>
</body>
</html>