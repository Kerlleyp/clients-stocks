<?php

    require_once("conexao.php");

    $stmt = $conn->query("SELECT
        compras.id AS compra_id,
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('templates/header.php'); ?>
    <main>
        <div class="compras-container">

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
                <?php endif; ?>

                <h2 class="cliente-title"><?= $compra['cliente_nome'] ?></h2>

                <table class="tabela-compras">
                    <thead>
                        <tr>
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
                    <a class="btn excluir" href="compras/excluir_compra.php?id=<?= $compra['compra_id'] ?>">Excluir</a>
                </td>
            </tr>

        <?php endforeach; ?>

        <?php if($clienteAtual !== null): ?>
        </tbody>
        </table>

        <p class="total-cliente">
            Total do cliente: R$ <?= number_format($totalDaCompra, 2, ',', '.') ?>
        </p>
        <?php endif; ?>
    </main>
    <?php require_once('templates/footer.php'); ?>
</div>
</body>
</html>