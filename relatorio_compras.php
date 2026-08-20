<?php
session_start();
require_once("db/conexao.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$usuario = $_SESSION['usuario_id'];

$pesquisa = trim($_GET['pesquisa'] ?? '');

$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;

if ($pagina < 1) {
    $pagina = 1;
}

$limite = 10;

$idCompra = isset($_GET['id']) ? intval($_GET['id']) : 0;

$detalhes = [];


if ($idCompra > 0) {

    $sqlDetalhes = "
        SELECT
            c.id AS compra_id,
            c.data_compra,
            cl.nome AS cliente,
            e.nome AS produto,
            ic.quantidade,
            ic.preco_unitario,
            ic.subtotal

        FROM compras c

        INNER JOIN clientes cl
            ON cl.id = c.cliente_id

        INNER JOIN itens_compra ic
            ON ic.compra_id = c.id

        INNER JOIN estoque e
            ON e.id = ic.produto_id

        WHERE c.id = :compra_id
        AND c.usuario_id = :usuario_id

        ORDER BY e.nome ASC
    ";

    $stmtDetalhes = $conn->prepare($sqlDetalhes);

    $stmtDetalhes->bindValue(
        ':compra_id',
        $idCompra,
        PDO::PARAM_INT
    );

    $stmtDetalhes->bindValue(
        ':usuario_id',
        $usuario,
        PDO::PARAM_INT
    );

    $stmtDetalhes->execute();

    $detalhes = $stmtDetalhes->fetchAll(PDO::FETCH_ASSOC);
}

$sqlTotal = "
    SELECT COUNT(DISTINCT c.id)

    FROM compras c

    INNER JOIN clientes cl
        ON cl.id = c.cliente_id

    LEFT JOIN itens_compra ic
        ON ic.compra_id = c.id

    LEFT JOIN estoque e
        ON e.id = ic.produto_id

    WHERE c.usuario_id = :usuario_id
";

$paramsTotal = [
    ':usuario_id' => $usuario
];

if ($pesquisa !== '') {

    $sqlTotal .= "
        AND (
            cl.nome LIKE :pesquisa
            OR e.nome LIKE :pesquisa
        )
    ";

    $paramsTotal[':pesquisa'] = "%$pesquisa%";
}

$stmtTotal = $conn->prepare($sqlTotal);

$stmtTotal->execute($paramsTotal);

$totalCompras = $stmtTotal->fetchColumn();

$totalPaginas = ceil($totalCompras / $limite);

if ($totalPaginas > 0 && $pagina > $totalPaginas) {
    $pagina = $totalPaginas;
}

$offset = ($pagina - 1) * $limite;

$sql = "
    SELECT DISTINCT
        c.id AS compra_id,
        c.data_compra,
        cl.nome AS cliente

    FROM compras c

    INNER JOIN clientes cl
        ON cl.id = c.cliente_id

    LEFT JOIN itens_compra ic
        ON ic.compra_id = c.id

    LEFT JOIN estoque e
        ON e.id = ic.produto_id

    WHERE c.usuario_id = :usuario_id
";

$params = [
    ':usuario_id' => $usuario
];

if ($pesquisa !== '') {

    $sql .= "
        AND (
            cl.nome LIKE :pesquisa
            OR e.nome LIKE :pesquisa
        )
    ";

    $params[':pesquisa'] = "%$pesquisa%";
}

$sql .= "
    ORDER BY c.id DESC
    LIMIT :limite OFFSET :offset
";

$stmt = $conn->prepare($sql);

$stmt->bindValue(
    ':usuario_id',
    $usuario,
    PDO::PARAM_INT
);

if ($pesquisa !== '') {

    $stmt->bindValue(
        ':pesquisa',
        "%$pesquisa%",
        PDO::PARAM_STR
    );
}

$stmt->bindValue(
    ':limite',
    $limite,
    PDO::PARAM_INT
);

$stmt->bindValue(
    ':offset',
    $offset,
    PDO::PARAM_INT
);

$stmt->execute();

$compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Relatório de Compras</title>

    <link
        rel="stylesheet"
        href="css/style.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>


<body>

    <?php require_once('templates/header.php'); ?>


    <main class="page-list">

        <h2 class="title-list">
            📊 Histórico de Compras dos Clientes
        </h2>

        <p class="separador">
            Visualize as compras feitas.
        </p>


        <div class="list-card">

            <div class="topo-tabela">

                <div class="topo-direita">

                    <form method="GET">

                        <input
                            type="text"
                            name="pesquisa"
                            placeholder="Buscar cliente ou produto..."
                            value="<?= htmlspecialchars($pesquisa) ?>">

                        <button
                            type="submit"
                            class="btn-novo">
                            Buscar
                        </button>

                    </form>

                </div>

            </div>

            <table class="table-container">

                <tr class="color-relatorio">

                    <th>
                        <i class="fa-solid fa-calendar"></i>
                        Data
                    </th>

                    <th>
                        <i class="fa-solid fa-user"></i>
                        Cliente
                    </th>

                    <th>
                        <i class="fa-solid fa-circle-check"></i>
                        Status
                    </th>

                    <th>
                        <i class="fa-solid fa-align-left"></i>
                        Detalhes
                    </th>

                </tr>


                <?php if (count($compras) > 0): ?>


                    <?php foreach ($compras as $venda): ?>

                        <tr>

                            <td>

                                <?= date(
                                    "d/m/Y H:i",
                                    strtotime($venda['data_compra'])
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $venda['cliente']
                                ) ?>

                            </td>


                            <td>

                                Concluída

                            </td>


                            <td>

                                <a
                                    href="?id=<?= $venda['compra_id'] ?>&pagina=<?= $pagina ?>&pesquisa=<?= urlencode($pesquisa) ?>#detalhes-<?= $venda['compra_id'] ?>">

                                    Ver detalhes

                                </a>

                            </td>

                        </tr>


                        <?php

                        if (
                            isset($detalhes[0]) &&
                            $detalhes[0]['compra_id'] == $venda['compra_id']
                        ):
                        ?>

                            <tr
                                id="detalhes-<?= $venda['compra_id'] ?>">

                                <td colspan="4">


                                    <div class="detalhes-flutuante">

                                        <a
                                            href="?pagina=<?= $pagina ?>&pesquisa=<?= urlencode($pesquisa) ?>"
                                            class="fechar">
                                            X
                                        </a>


                                        <h3>

                                            Venda #<?= $detalhes[0]['compra_id'] ?>

                                        </h3>


                                        <p>

                                            <strong>
                                                Cliente:
                                            </strong>

                                            <?= htmlspecialchars(
                                                $detalhes[0]['cliente']
                                            ) ?>

                                        </p>


                                        <p>

                                            <strong>
                                                Data:
                                            </strong>

                                            <?= date(
                                                "d/m/Y H:i",
                                                strtotime(
                                                    $detalhes[0]['data_compra']
                                                )
                                            ) ?>

                                        </p>


                                        <hr>


                                        <?php

                                        $totalCompra = 0;

                                        foreach ($detalhes as $item):

                                            $totalCompra += $item['subtotal'];

                                        ?>

                                            <p>

                                                <strong>
                                                    Produto:
                                                </strong>

                                                <?= htmlspecialchars(
                                                    $item['produto']
                                                ) ?>

                                                <br>


                                                <strong>
                                                    Quantidade:
                                                </strong>

                                                <?= $item['quantidade'] ?>

                                                <br>


                                                <strong>
                                                    Preço unitário:
                                                </strong>

                                                R$

                                                <?= number_format(
                                                    $item['preco_unitario'],
                                                    2,
                                                    ',',
                                                    '.'
                                                ) ?>

                                                <br>


                                                <strong>
                                                    Subtotal:
                                                </strong>

                                                R$

                                                <?= number_format(
                                                    $item['subtotal'],
                                                    2,
                                                    ',',
                                                    '.'
                                                ) ?>

                                            </p>


                                            <hr>


                                        <?php endforeach; ?>


                                        <h3>

                                            Total da compra:

                                            R$

                                            <?= number_format(
                                                $totalCompra,
                                                2,
                                                ',',
                                                '.'
                                            ) ?>

                                        </h3>


                                    </div>

                                </td>

                            </tr>


                        <?php endif; ?>


                    <?php endforeach; ?>


                <?php else: ?>


                    <tr>

                        <td colspan="4">

                            Nenhuma compra encontrada.

                        </td>

                    </tr>


                <?php endif; ?>


            </table>

            <?php if ($totalPaginas > 1): ?>

                <div class="paginacao">


                    <!-- ANTERIOR -->

                    <?php if ($pagina > 1): ?>

                        <a
                            href="?pagina=<?= $pagina - 1 ?>&pesquisa=<?= urlencode($pesquisa) ?>"
                            class="pagina-btn">

                            <i class="fa-solid fa-chevron-left"></i>

                        </a>

                    <?php endif; ?>


                    <!-- NÚMEROS -->

                    <?php for (
                        $i = 1;
                        $i <= $totalPaginas;
                        $i++
                    ): ?>

                        <a
                            href="?pagina=<?= $i ?>&pesquisa=<?= urlencode($pesquisa) ?>"
                            class="pagina-btn <?= ($pagina == $i) ? 'ativa' : '' ?>">

                            <?= $i ?>

                        </a>

                    <?php endfor; ?>


                    <!-- PRÓXIMA -->

                    <?php if ($pagina < $totalPaginas): ?>

                        <a
                            href="?pagina=<?= $pagina + 1 ?>&pesquisa=<?= urlencode($pesquisa) ?>"
                            class="pagina-btn">

                            <i class="fa-solid fa-chevron-right"></i>

                        </a>

                    <?php endif; ?>


                </div>

            <?php endif; ?>


        </div>

    </main>


    <?php require_once('templates/footer.php'); ?>


</body>

</html>