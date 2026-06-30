<?php
    session_start();
    require_once("db/conexao.php");

    $usuario = $_SESSION["usuario_id"];

    $stmt = $conn->prepare("
        SELECT 
            itens_compra.produto_id,
            SUM(itens_compra.quantidade) AS total_vendido,
            estoque.nome,
            estoque.marca,
            estoque.descricao,
            estoque.preco
        FROM itens_compra
        JOIN estoque ON itens_compra.produto_id = estoque.id
        WHERE estoque.usuario_id = :usuario_id
        GROUP BY 
            itens_compra.produto_id,
            estoque.nome,
            estoque.marca,
            estoque.descricao,
            estoque.preco
        ORDER BY total_vendido DESC
    ");

    $stmt->execute([
        ':usuario_id' => $usuario
    ]);

    $maisVendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatorio Vendas</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <?php require_once('templates/header.php') ?>
    <main  class="page-list">
        <h2 class="title-list">📊 Relatórios</h2>
        <p class="separador">Visualize os produtos mais vendidos no estoque.</p>
        <div class="list-card">
            <div class="topo-tabela">
                <div class="topo-direita">
                    <input type="text" placeholder="Buscar Produto...">
                </div>
            </div>
            <!--Mostra os Clientes-->
            <table class="table-container">
                <tr class="color-relatorio">
                    <th><i class="fa-solid fa-box"></i> Nome</th>
                    <th><i class="fa-solid fa-tags"></i> Marca</th>
                    <th><i class="fa-solid fa-align-left"></i> Descrição</th>
                    <th><i class="fa-solid fa-cubes-stacked"></i> Quantidade</th>
                    <th><i class="fa-solid fa-dollar-sign"></i> Preço</th>
                </tr>
                <?php foreach($maisVendidos as $maisVendas): ?> 
                    <tr class="table-cor">
                        <td><?= $maisVendas["nome"] ?></td>
                        <td><?= $maisVendas["marca"] ?></td>
                        <td><?= $maisVendas["descricao"] ?></td>
                        <td><?= $maisVendas["total_vendido"] ?></td>
                        <td><?= 'R$ ' . number_format($maisVendas["preco"], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?> 
            </table>
        </div>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>