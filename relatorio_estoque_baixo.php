<?php
session_start();
require_once("db/conexao.php");
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php");
        exit;
    }

$usuario_id = $_SESSION["usuario_id"];

$pesquisa = "";
$pagina = 1;
$limite = 10;


if(isset($_GET["pagina"])) {
    $pagina = intval($_GET["pagina"]);
}

$offset = ($pagina - 1) * $limite;


if(isset($_GET["pesquisa"])) {
    $pesquisa = trim($_GET["pesquisa"]);
}


// DASHBOARD
$stmtDashboard = $conn->prepare("
    SELECT
        SUM(CASE WHEN quantidade BETWEEN 4 AND 10 THEN 1 ELSE 0 END) AS produtos_baixos,
        SUM(CASE WHEN quantidade <= 3 THEN 1 ELSE 0 END) AS produtos_criticos
    FROM estoque
    WHERE usuario_id = :usuario_id
");

$stmtDashboard->bindParam(":usuario_id", $usuario_id);
$stmtDashboard->execute();

$dashboard = $stmtDashboard->fetch(PDO::FETCH_ASSOC);


// PRODUTOS COM ESTOQUE BAIXO
if($pesquisa == "") {

    $stmtBaixo = $conn->prepare("
        SELECT *
        FROM estoque
        WHERE usuario_id = :usuario_id
        AND quantidade <= 10
        ORDER BY quantidade ASC
        LIMIT :limite OFFSET :offset
    ");

    $stmtBaixo->bindParam(":usuario_id", $usuario_id);
    $stmtBaixo->bindParam(":limite", $limite, PDO::PARAM_INT);
    $stmtBaixo->bindParam(":offset", $offset, PDO::PARAM_INT);


} else {

    $pesquisa = "%" . $pesquisa . "%";

    $stmtBaixo = $conn->prepare("
        SELECT *
        FROM estoque
        WHERE usuario_id = :usuario_id
        AND quantidade <= 10
        AND (
            nome LIKE :pesquisa
            OR marca LIKE :pesquisa
            OR descricao LIKE :pesquisa
        )
        ORDER BY quantidade ASC
        LIMIT :limite OFFSET :offset
    ");

    $stmtBaixo->bindParam(":usuario_id", $usuario_id);
    $stmtBaixo->bindParam(":pesquisa", $pesquisa);
    $stmtBaixo->bindParam(":limite", $limite, PDO::PARAM_INT);
    $stmtBaixo->bindParam(":offset", $offset, PDO::PARAM_INT);

}


$stmtBaixo->execute();

$baixo = $stmtBaixo->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatorio Baixo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <?php require_once('templates/header.php'); ?>
<main class="page-list">
    <h2 class="title-list">📊 Monitoramento do Estoque</h2>
    <p class="separador">Visualize os produtos com estoque baixo e crítico.</p>
    <div class="dashboard">
        <div class="card-dashboard">
            <div class="icone-resumo clientes">
                <i>🟡</i>
            </div>
            <div class="texto-resumo">
                <h2><?= $dashboard["produtos_baixos"] ?? 0 ?></h2>
                <span>Produtos Baixos</span>
            </div>
        </div>
        <div class="card-dashboard">
            <div class="icone-resumo clientes">
                <i>🔴</i>
            </div>

            <div class="texto-resumo">
                <h2><?= $dashboard["produtos_criticos"] ?? 0 ?></h2>
                <span>Produtos Críticos</span>
            </div>
        </div>
    </div>
    <div class="list-card">
        <div class="topo-tabela">
            <div class="topo-direita">
                <form method="GET">
                    <input 
                        type="text" 
                        name="pesquisa" 
                        placeholder="Buscar Produto..." 
                        value="<?= htmlspecialchars($_GET["pesquisa"] ?? "") ?>"
                    >
                </form>
            </div>
        </div>
        <table class="table-container">
            <tr class="color-relatorio">
                <th>
                    <i class="fa-solid fa-box"></i> Nome
                </th>
                <th>
                    <i class="fa-solid fa-tags"></i> Marca
                </th>
                <th>
                    <i class="fa-solid fa-align-left"></i> Descrição
                </th>
                <th>
                    <i class="fa-solid fa-cubes-stacked"></i> Quantidade
                </th>
                <th>
                    <i class="fa-solid fa-dollar-sign"></i> Preço
                </th>
                <th>
                    <i class="fa-solid fa-circle-check"></i> Status
                </th>
            </tr>
            <?php foreach($baixo as $produto): ?>
                <?php
                    if($produto["quantidade"] <= 3) {
                        $icone = "🔴 Crítico";
                        $classe = "critico";
                    } else {
                        $icone = "🟡 Baixo";
                        $classe = "baixo";
                    }
                ?>
                <tr class="<?= $classe ?> table-cor">
                    <td><?= htmlspecialchars($produto["nome"]) ?></td>
                    <td><?= htmlspecialchars($produto["marca"]) ?></td>
                    <td><?= htmlspecialchars($produto["descricao"]) ?></td>
                    <td><?= htmlspecialchars($produto["quantidade"]) ?></td>
                    <td>
                        R$ <?= number_format($produto["preco"], 2, ',', '.') ?>
                    </td>
                    <td><?= $icone ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>