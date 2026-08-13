<?php
session_start();
require_once("db/conexao.php");
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}


$usuario = $_SESSION["usuario_id"];


$detalhes = null;


if (isset($_GET['id'])) {

    $compra_id = $_GET['id'];

    $stmtDetalhes = $conn->prepare("
        SELECT 
            compras.id AS compra_id,
            compras.data_compra,
            clientes.nome AS cliente,
            estoque.nome AS produto,
            itens_compra.quantidade,
            itens_compra.preco_unitario,
            itens_compra.subtotal

        FROM compras

        JOIN clientes 
            ON compras.cliente_id = clientes.id

        JOIN itens_compra 
            ON compras.id = itens_compra.compra_id

        JOIN estoque 
            ON itens_compra.produto_id = estoque.id

        WHERE compras.id = :id
    ");

    $stmtDetalhes->execute([
        ":id" => $compra_id
    ]);

    $detalhes = $stmtDetalhes->fetchAll(PDO::FETCH_ASSOC);
}

$stmt = $conn->prepare("
    SELECT 
        compras.id AS compra_id,
        compras.data_compra,
        clientes.nome AS cliente,
        estoque.nome AS produto,
        itens_compra.quantidade,
        itens_compra.subtotal AS total_item

    FROM compras

    JOIN clientes 
        ON compras.cliente_id = clientes.id

    JOIN itens_compra 
        ON compras.id = itens_compra.compra_id

    JOIN estoque 
        ON itens_compra.produto_id = estoque.id

    WHERE compras.usuario_id = :usuario_id

    ORDER BY compras.data_compra DESC
");


$stmt->execute([
    ":usuario_id" => $usuario
]);


$vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatorio Compras</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php require_once('templates/header.php'); ?>
    <main class="page-list">
        <h2 class="title-list">📊 Histórico de Compras dos Clientes</h2>
        <p class="separador">Visualize as compras feitas.</p>
        </div>
        <div class="list-card">
            <div class="topo-tabela">
                <div class="topo-direita">
                    <form method="GET">
                        <input
                            type="text"
                            name="pesquisa"
                            placeholder="Buscar Produto..."
                            value="<?= htmlspecialchars($_GET["pesquisa"] ?? "") ?>">
                    </form>
                </div>
            </div>
            <table class="table-container">
                <tr class="color-relatorio">
                    <th>
                        <i></i> Data
                    </th>
                    <th>
                        <i class="fa-solid fa-box"></i> Cliente
                    </th>
                    <th>
                        <i class="fa-solid fa-circle-check"></i> Status
                    </th>
                    <th>
                        <i class="fa-solid fa-align-left"></i> Detalhes
                    </th>
                </tr>
                <?php foreach ($vendas as $venda): ?>

                    <tr>
                        <td>
                            <?= date("d/m/Y H:i", strtotime($venda['data_compra'])) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($venda['cliente']) ?>
                        </td>

                        <td>
                            Concluída
                        </td>
                        <td>
                            <a href="?id=<?= $venda['compra_id'] ?>#detalhes-<?= $venda['compra_id'] ?>">
                                Ver detalhes
                            </a>
                        </td>
                    </tr>
                    <?php if (isset($detalhes) && $detalhes[0]['compra_id'] == $venda['compra_id']): ?>

                        <tr id="detalhes-<?= $venda['compra_id'] ?>">
                            <td colspan="7">

                                <div class="detalhes-flutuante">

                                    <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" class="fechar">
                                        X
                                    </a>

                                    <h3>
                                        Venda #<?= $detalhes[0]['compra_id'] ?>
                                    </h3>

                                    <p>
                                        <strong>Cliente:</strong>
                                        <?= htmlspecialchars($detalhes[0]['cliente']) ?>
                                    </p>

                                    <p>
                                        <strong>Data:</strong>
                                        <?= date("d/m/Y H:i", strtotime($detalhes[0]['data_compra'])) ?>
                                    </p>

                                    <hr>

                                    <?php foreach ($detalhes as $item): ?>

                                        <p>
                                            <strong>Produto:</strong>
                                            <?= htmlspecialchars($item['produto']) ?>
                                            <br>

                                            <strong>Quantidade:</strong>
                                            <?= $item['quantidade'] ?>
                                            <br>

                                            <strong>Preço:</strong>
                                            R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?>
                                            <br>

                                            <strong>Total:</strong>
                                            R$ <?= number_format($item['subtotal'], 2, ',', '.') ?>
                                        </p>

                                        <hr>

                                    <?php endforeach; ?>

                                </div>

                            </td>
                        </tr>

                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        </div>

    </main>
    <?php require_once('templates/footer.php'); ?>
</body>

</html>