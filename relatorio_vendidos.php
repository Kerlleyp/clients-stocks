<?php

    require_once("db/conexao.php");

    $stmt = $conn->query("SELECT produto_id, SUM(itens_compra.quantidade) AS total_vendido, estoque.nome, estoque.marca, estoque.descricao, estoque.preco FROM itens_compra JOIN estoque ON itens_compra.produto_id = estoque.id GROUP BY produto_id, estoque.nome, estoque.marca, estoque.descricao, estoque.preco ORDER BY total_vendido DESC");

    $maisVendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatorio Vendas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('templates/header.php') ?>
       <main>
        <!--Mostra os Produtos em Baixa-->
        <table class="table-container">
            <tr id="color-estoque">
                <th>Nome</th>
                <th>Marca</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Preço</th>
            </tr>

            <?php foreach($maisVendidos as $maisVendas): ?> 
                <tr>
                    <td><?= $maisVendas["nome"] ?></td>
                    <td><?= $maisVendas["marca"] ?></td>
                    <td><?= $maisVendas["descricao"] ?></td>
                    <td><?= $maisVendas["total_vendido"] ?></td>
                    <td><?= 'R$ ' . number_format($maisVendas["preco"], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?> 
        </table>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>