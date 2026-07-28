<?php
    session_start();
    require_once("db/conexao.php");

    $usuario_id = $_SESSION['usuario_id'];
    $pesquisa = "";
    $pagina = 1;
    $limite = 10;
    $offset = 0;

    if(isset($_GET["pagina"])) {
        $pagina = intval($_GET["pagina"]);
    }

    $offset = ($pagina - 1) * $limite;

    if(isset($_GET["pesquisa"])) {
        $pesquisa = trim($_GET["pesquisa"]);
    }

    if($pesquisa === "") {

        $stmt = $conn->prepare(" SELECT * FROM estoque WHERE  usuario_id = :usuario_id LIMIT :limite OFFSET :offset");

        $stmtTotal = $conn->prepare("SELECT COUNT(*) AS total FROM estoque WHERE usuario_id = :usuario_id");

        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        $stmtTotal->bindParam(":usuario_id", $usuario_id);

        $stmt->execute();
        $stmtTotal->execute();

        $estoques = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {

        $stmt = $conn->prepare("SELECT * FROM estoque WHERE usuario_id = :usuario_id AND ( nome LIKE :pesquisa OR marca LIKE :pesquisa OR descricao LIKE :pesquisa) LIMIT :limite OFFSET :offset");
        $pesquisa = '%' . $pesquisa . '%';

        $stmtTotal = $conn->prepare("SELECT COUNT(*) AS total FROM estoque WHERE usuario_id = :usuario_id AND ( nome LIKE :pesquisa OR marca LIKE :pesquisa OR descricao LIKE :pesquisa)");

        $stmt->bindParam(":pesquisa", $pesquisa);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        $stmtTotal->bindParam(":usuario_id", $usuario_id);
        $stmtTotal->bindParam(":pesquisa", $pesquisa);

        $stmt->execute();
        $stmtTotal->execute();

        $estoques = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    }
    
    $totalProdutos = $stmtTotal->fetch(PDO::FETCH_ASSOC);
    $totalPaginas = ceil($totalProdutos["total"] / $limite);

    if($totalPaginas < 1) {
        $totalPaginas = 1;
    }

    $stmtDashboard = $conn->prepare("SELECT  COUNT(*) AS total_produtos, SUM(preco * quantidade) AS valor_estoque, SUM(quantidade) AS quantidade_total FROM estoque WHERE usuario_id = :usuario_id");

    $stmtDashboard->bindParam(":usuario_id", $usuario_id);
    $stmtDashboard->execute();

    $dashboard = $stmtDashboard->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <?php require_once('templates/header.php') ?>
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
    <main  class="page-list">
        <h2 class="title-list">📦Estoque</h2>
        <p class="separador">Gerencie os produtos cadastrados no sistema.</p>
        <div class="dashboard">
            <div class="card-dashboard">
                <div class="icone-resumo clientes">
                    <i >📦</i>
                </div>
                <div class="texto-resumo">
                    <h2><?= $dashboard["total_produtos"] ?></h2>
                    <span>Produtos cadastrados</span>
                </div>
            </div>
            <div class="card-dashboard">
                <div class="icone-resumo clientes">
                    <i class="fa-solid fa-money-bill financeiro"></i>
                </div>
                <div class="texto-resumo">
                    <h2><?= number_format($dashboard["valor_estoque"] ?? 0, 2, ',', '.') ?></h2>
                    <span>Valor do estoque</span>
                </div>
            </div>
        </div>
        <div class="list-card">
            <div class="topo-tabela">
                <div class="topo-esquerda">
                    <a href="estoque.php" class="btn-novo">
                        <i class="fa-solid fa-plus"></i> Novo Produto
                    </a>
                </div>

                <div class="topo-direita">
                    <form method="get">
                        <input type="text" name="pesquisa" placeholder="Buscar Produto...">
                    </form>
                </div>
            </div>
            <table class="table-container">
                <tr id="color-estoque">
                   <th><i class="fa-solid fa-box"></i> Nome</th>
                    <th><i class="fa-solid fa-building"></i> Marca</th>
                    <th><i class="fa-solid fa-file-lines"></i> Descrição</th>
                    <th><i class="fa-solid fa-cubes"></i> Quantidade</th>
                    <th><i class="fa-solid fa-money-bill-wave"></i> Preço</th>
                    <th><i class="fa-solid fa-gear"></i> Ações</th>
                </tr>

                <?php foreach($estoques as $estoque): ?>
                <tr class="table-cor">
                    <td><?= $estoque["nome"] ?></td>
                    <td><?= $estoque["marca"] ?></td>
                    <td><?= $estoque["descricao"] ?></td>
                    <td><?= $estoque["quantidade"] ?></td>
                    <td><?= 'R$ ' . number_format($estoque["preco"], 2, ',', '.') ?></td>
                    <td>
                        <a class="btn editar" href="estoque/editar_estoque.php?id=<?= $estoque['id'] ?>"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                        <a class="btn excluir" href="estoque/excluir_estoque.php?id=<?= $estoque['id'] ?>"><i class="fa-solid fa-trash"></i> Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </table>
            <div class="paginacao">
                <?php if($pagina > 1): ?>
                    <a href="?pagina=<?= $pagina - 1 ?>&pesquisa=<?= $pesquisa ?>" class="pagina-btn">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php endif; ?>
                <?php for($i = 1; $i <= $totalPaginas; $i++): ?>
                    <a href="?pagina=<?= $i ?>&pesquisa=<?= $pesquisa ?>" 
                        class="pagina-btn <?= ($pagina == $i) ? 'ativa' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                <?php if($pagina < $totalPaginas): ?>
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