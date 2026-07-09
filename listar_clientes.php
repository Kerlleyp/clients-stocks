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

        $stmt = $conn->prepare(" SELECT * FROM clientes WHERE  usuario_id = :usuario_id LIMIT :limite OFFSET :offset");

        $stmtTotal = $conn->prepare("SELECT COUNT(*) AS total FROM clientes WHERE usuario_id = :usuario_id");

        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        $stmtTotal->bindParam(":usuario_id", $usuario_id);

        $stmt->execute();
        $stmtTotal->execute();

        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {

        $stmt = $conn->prepare("SELECT * FROM clientes WHERE usuario_id = :usuario_id AND ( nome LIKE :pesquisa OR telefone LIKE :pesquisa OR endereco LIKE :pesquisa) LIMIT :limite OFFSET :offset");
        $pesquisa = '%' . $pesquisa . '%';

        $stmtTotal = $conn->prepare("SELECT COUNT(*) AS total FROM clientes WHERE usuario_id = :usuario_id AND ( nome LIKE :pesquisa OR telefone LIKE :pesquisa OR endereco LIKE :pesquisa)");

        $stmt->bindParam(":pesquisa", $pesquisa);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        $stmtTotal->bindParam(":usuario_id", $usuario_id);
        $stmtTotal->bindParam(":pesquisa", $pesquisa);

        $stmt->execute();
        $stmtTotal->execute();

        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    }
    
    $totalProdutos = $stmtTotal->fetch(PDO::FETCH_ASSOC);
    $totalPaginas = ceil($totalProdutos["total"] / $limite);

    if($totalPaginas < 1) {
        $totalPaginas = 1;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
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
        <h2 class="title-list "><i class="fa-solid fa-users title-icon-client"></i> Clientes</h2>
        <p class="separador">Gerencie os clientes cadastrados no sistema</p>
        <div class="dashboard">
            <div class="card-dashboard">
                <div class="icone-resumo clientes">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="texto-resumo">
                    <h2><?= count($clientes) ?></h2>
                    <span>Clientes cadastrados</span>
                </div>
            </div>
        </div>
        <div class="list-card">
            <div class="topo-tabela">
                <div class="topo-esquerda">
                    <a href="cliente.php" class="btn-novo">
                        <i class="fa-solid fa-plus"></i> Novo Cliente
                    </a>
                </div>

                <div class="topo-direita">
                    <form method="get">
                        <input type="text" name="pesquisa" placeholder="Buscar Produto...">
                    </form>
                </div>
            </div>
            <!--Mostra os Clientes-->
            <table class="table-container">
                <tr id="color-clientes">
                    <th><i class="fa-solid fa-user"></i> Nome</th>
                    <th><i class="fa-solid fa-phone"></i> Telefone</th>
                    <th><i class="fa-solid fa-location-dot"></i> Endereço</th>
                    <th><i class="fa-solid fa-gear"></i> Ações</th>
                </tr>

                <?php foreach($clientes as $cliente): ?>
                    <tr class="table-cor">
                        <td>
                            <div class="list-info">
                                <span class="user-avatar-list">
                                    <?= mb_strtoupper(mb_substr($cliente["nome"], 0, 1)) ?>
                                </span>

                                <span class="cliente-nome">
                                    <?= $cliente["nome"] ?>
                                </span>
                            </div>
                        </td>
                        <td><?= $cliente["telefone"] ?></td>
                        <td><?= $cliente["endereco"] ?></td>
                        <td>
                            <a class="btn editar" href="clientes/editar_cliente.php?id=<?= $cliente['id'] ?>"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                            <a class="btn excluir" href="clientes/excluir_clientes.php?id=<?= $cliente['id'] ?>"><i class="fa-solid fa-trash"></i> Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>