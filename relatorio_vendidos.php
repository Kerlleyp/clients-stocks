<?php

session_start();
require_once("db/conexao.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$usuario = $_SESSION["usuario_id"];

$pesquisa = "";
$pagina = 1;
$limite = 10;

if (isset($_GET["pagina"])) {
    $pagina = max(1, intval($_GET["pagina"]));
}

$offset = ($pagina - 1) * $limite;

if (isset($_GET["pesquisa"])) {
    $pesquisa = trim($_GET["pesquisa"]);
}

$pesquisaSql = "%" . $pesquisa . "%";

$stmt = $conn->prepare("
    SELECT 
        itens_compra.produto_id,
        SUM(itens_compra.quantidade) AS total_vendido,
        estoque.nome,
        estoque.marca,
        estoque.descricao,
        estoque.preco
    FROM itens_compra
    JOIN estoque 
        ON itens_compra.produto_id = estoque.id
    WHERE estoque.usuario_id = :usuario_id
      AND (
          estoque.nome LIKE :pesquisa
          OR estoque.marca LIKE :pesquisa
          OR estoque.descricao LIKE :pesquisa
      )
    GROUP BY 
        itens_compra.produto_id,
        estoque.nome,
        estoque.marca,
        estoque.descricao,
        estoque.preco
    ORDER BY total_vendido DESC
    LIMIT :limite OFFSET :offset
");

$stmt->bindParam(":usuario_id", $usuario, PDO::PARAM_INT);
$stmt->bindParam(":pesquisa", $pesquisaSql);
$stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
$stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

$stmt->execute();

$maisVendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtTotal = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM (
        SELECT itens_compra.produto_id
        FROM itens_compra
        JOIN estoque 
            ON itens_compra.produto_id = estoque.id
        WHERE estoque.usuario_id = :usuario_id
          AND (
              estoque.nome LIKE :pesquisa
              OR estoque.marca LIKE :pesquisa
              OR estoque.descricao LIKE :pesquisa
          )
        GROUP BY itens_compra.produto_id
    ) AS produtos
");

$stmtTotal->bindParam(":usuario_id", $usuario, PDO::PARAM_INT);
$stmtTotal->bindParam(":pesquisa", $pesquisaSql);

$stmtTotal->execute();

$totalProdutos = $stmtTotal->fetch(PDO::FETCH_ASSOC);

$totalPaginas = ceil($totalProdutos["total"] / $limite);

if ($totalPaginas < 1) {
    $totalPaginas = 1;
}

$produtoTop = $maisVendidos[0] ?? null;

$total = $produtoTop
    ? $produtoTop["total_vendido"] * $produtoTop["preco"]
    : 0;

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
    <main class="page-list">
        <h2 class="title-list">📊 Produtos em Destaque</h2>
        <p class="separador">Visualize os produtos mais vendidos no estoque.</p>
        <div class="dashboard">
            <div class="card-dashboard">
                <div class="icone-resumo clientes">
                    <i>🏆</i>
                </div>
                <div class="texto-resumo">
                    <h2>
                        <?= $produtoTop["nome"] ?? "Nenhum" ?>
                    </h2>
                    <span>Produto mais vendido</span>
                </div>
            </div>
            <div class="card-dashboard">
                <div class="icone-resumo clientes">
                    <i>💰</i>
                </div>
                <div class="texto-resumo">
                    <h2>
                        <?= $produtoTop ? $produtoTop["total_vendido"] : 0 ?>
                    </h2>
                    <span>Quantidade vendida</span>
                </div>
            </div>
            <div class="card-dashboard">
                <div class="icone-resumo clientes">
                    <i class="fa-solid fa-money-bill financeiro"></i>
                </div>
                <div class="texto-resumo">
                    <h2>
                        <?= $total ?>
                    </h2>
                    <span>Preço total vendido</span>
                </div>
            </div>
        </div>
        <div class="list-card">
            <div class="topo-tabela">
                <div class="topo-direita">
                    <form method="get">
                        <input type="text" name="pesquisa" placeholder="Buscar Produto...">
                        <button type="submit" class="btn-novo">Buscar</button>
                    </form>
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
                <?php foreach ($maisVendidos as $maisVendas): ?>
                    <tr class="table-cor">
                        <td><?= htmlspecialchars($maisVendas["nome"]) ?></td>
                        <td><?= htmlspecialchars($maisVendas["marca"]) ?></td>
                        <td><?= htmlspecialchars($maisVendas["descricao"]) ?></td>
                        <td><?= htmlspecialchars($maisVendas["total_vendido"]) ?></td>
                        <td><?= 'R$ ' . number_format($maisVendas["preco"], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="paginacao">
                <?php if ($pagina > 1): ?>
                    <a href="?pagina=<?= $pagina - 1 ?>&pesquisa=<?= $pesquisa ?>" class="pagina-btn">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <a href="?pagina=<?= $i ?>&pesquisa=<?= $pesquisa ?>"
                        class="pagina-btn <?= ($pagina == $i) ? 'ativa' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                <?php if ($pagina < $totalPaginas): ?>
                    <a href="?pagina=<?= $pagina + 1 ?>&pesquisa=<?= $pesquisa ?>" class="pagina-btn">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>

</html>