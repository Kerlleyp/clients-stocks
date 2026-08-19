<?php
session_start();
require_once("db/conexao.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

if (isset($_GET['editar'])) {
    $editar = $_GET['editar'];
} else {
    $editar = null;
}


$pesquisa = "";
$pagina = 1;
$limite = 10;
$offset = 0;

if (isset($_GET["pagina"])) {
    $pagina = max(1, intval($_GET["pagina"]));
}

$offset = ($pagina - 1) * $limite;

if (isset($_GET["pesquisa"])) {
    $pesquisa = trim($_GET["pesquisa"]);
}

if ($pesquisa == "") {

    $sql = "
        SELECT
            compras.id AS compra_id,
            clientes.nome AS cliente_nome,
            estoque.id AS produto_id,
            estoque.nome AS produto_nome,
            estoque.preco,
            itens_compra.id AS item_compra_id,
            SUM(itens_compra.quantidade) AS quantidade_total

        FROM compras

        INNER JOIN clientes
            ON compras.cliente_id = clientes.id

        INNER JOIN itens_compra
            ON itens_compra.compra_id = compras.id

        INNER JOIN estoque
            ON itens_compra.produto_id = estoque.id

        WHERE compras.usuario_id = :usuario_id

        GROUP BY
            compras.id,
            clientes.nome,
            estoque.id,
            estoque.nome,
            estoque.preco

        ORDER BY clientes.nome ASC

        LIMIT :limite OFFSET :offset
    ";

    $stmtClientes = $conn->prepare("
        SELECT COUNT(DISTINCT cliente_id) AS total_clientes
        FROM compras
        WHERE usuario_id = :usuario_id
    ");

    $stmtClientes->bindValue(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $stmtClientes->execute();
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $stmt->bindValue(":limite", $limite, PDO::PARAM_INT);
    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

    $stmt->execute();

    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $totalClientes = $stmtClientes->fetch(PDO::FETCH_ASSOC);

    $stmtValor = $conn->prepare("SELECT SUM(total) AS valor_total FROM compras WHERE usuario_id = :usuario_id");

    $stmtValor->bindParam(":usuario_id", $usuario_id);

    $stmtValor->execute();

    $valorCompras = $stmtValor->fetch(PDO::FETCH_ASSOC);

    $stmtTotal = $conn->prepare("
        SELECT COUNT(DISTINCT compras.id) AS total

        FROM compras

        INNER JOIN clientes
            ON compras.cliente_id = clientes.id

        WHERE compras.usuario_id = :usuario_id
    ");

    $stmtTotal->bindValue(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $stmtTotal->execute();
} else {

    $pesquisaLike = "%{$pesquisa}%";

    $sql = "
        SELECT
            compras.id AS compra_id,
            clientes.nome AS cliente_nome,
            estoque.id AS produto_id,
            estoque.nome AS produto_nome,
            estoque.preco,
            SUM(itens_compra.quantidade) AS quantidade_total

        FROM compras

        INNER JOIN clientes
            ON compras.cliente_id = clientes.id

        INNER JOIN itens_compra
            ON itens_compra.compra_id = compras.id

        INNER JOIN estoque
            ON itens_compra.produto_id = estoque.id

        WHERE compras.usuario_id = :usuario_id
        AND clientes.nome LIKE :pesquisa

        GROUP BY
            compras.id,
            clientes.nome,
            estoque.id,
            estoque.nome,
            estoque.preco

        ORDER BY clientes.nome ASC

        LIMIT :limite OFFSET :offset
    ";

    $stmtClientes = $conn->prepare("
        SELECT COUNT(DISTINCT cliente_id) AS total_clientes
        FROM compras
        WHERE usuario_id = :usuario_id
    ");

    $stmtClientes->bindValue(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $stmtClientes->execute();

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $stmt->bindValue(":pesquisa", $pesquisaLike);
    $stmt->bindValue(":limite", $limite, PDO::PARAM_INT);
    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

    $stmt->execute();

    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $totalClientes = $stmtClientes->fetch(PDO::FETCH_ASSOC);

    $stmtTotal = $conn->prepare("
        SELECT COUNT(DISTINCT compras.id) AS total

        FROM compras

        INNER JOIN clientes
            ON compras.cliente_id = clientes.id

        WHERE compras.usuario_id = :usuario_id
        AND clientes.nome LIKE :pesquisa
    ");

    $stmtTotal->bindValue(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $stmtTotal->bindValue(":pesquisa", $pesquisaLike);

    $stmtTotal->execute();
}

$totalProdutos = $stmtTotal->fetch(PDO::FETCH_ASSOC);

$totalPaginas = ceil($totalProdutos["total"] / $limite);

if ($totalPaginas < 1) {
    $totalPaginas = 1;
}

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

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
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
        <div class="dashboard">
            <div class="card-dashboard">
                <div class="icone-resumo clientes">
                    🛒
                </div>
                <div class="texto-resumo">
                    <h2><?= count($compras) ?></h2>
                    <span>Compras cadastradas</span>
                </div>
            </div>
            <div class="card-dashboard">
                <div class="icone-resumo clientes">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="texto-resumo">
                    <h2><?= $totalClientes['total_clientes'] ?></h2>
                    <span>Clientes</span>
                </div>
            </div>
            <div class="card-dashboard">
                <div class="icone-resumo clientes">
                    <i class="fa-solid fa-money-bill financeiro"></i>
                </div>
                <div class="texto-resumo">
                    <h2><?= number_format($valorCompras["valor_total"] ?? 0, 2, ',', '.') ?></h2>
                    <span>Total das Compras</span>
                </div>
            </div>
        </div>
        <div class="list-card">
            <div class="topo-tabela">
                <div class="topo-esquerda">
                    <a href="cliente.php" class="btn-novo">
                        <i class="fa-solid fa-plus"></i> Cadastrar Compras
                    </a>
                </div>

                <div class="topo-direita">
                    <form method="get">
                        <input type="text" name="pesquisa" placeholder="Buscar Cliente...">
                        <button type="submit" class="btn-novo">Buscar</button>
                    </form>
                </div>
            </div>

            <?php
            $clienteAtual = null;
            $totalDaCompra = 0;
            ?>

            <?php foreach ($compras as $compra): ?>
                <?php if ($clienteAtual != $compra['cliente_nome']): ?>

                    <?php if ($clienteAtual !== null): ?>
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

                        <tr id="item-<?= $compra['item_compra_id']; ?>">
                            <td><?= $compra['produto_nome'] ?></td>

                            <?php if ($compra['item_compra_id'] == $editar): ?>
                                <td>
                                    <form method="POST" action="compras/editar_compra.php">
                                        <input
                                            type="number"
                                            name="novaQuantidade"
                                            value="<?= $compra['quantidade_total'] ?>">
                                        <input
                                            type="hidden"
                                            name="item_compra_id"
                                            value="<?= $compra['item_compra_id'] ?>">
                                        <button type="submit" class="btn editar">
                                            Salvar
                                        </button>
                                    </form>
                                </td>
                            <?php else: ?>
                                <td>
                                    <?= $compra['quantidade_total'] ?>
                                </td>
                            <?php endif; ?>
                            <td>R$ <?= number_format($total_produto, 2, ',', '.') ?></td>
                            <td>
                                <a class="btn excluir" href="compras/excluir_compra.php?id=<?= $compra['compra_id'] ?>">
                                    <i class="fa-solid fa-trash"></i> Excluir
                                </a>
                                <a href="?editar=<?= $compra['item_compra_id']; ?>#item-<?= $compra['item_compra_id']; ?>" class="btn editar">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($clienteAtual !== null): ?>
                        </tbody>
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