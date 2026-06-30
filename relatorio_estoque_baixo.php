<?php
    session_start();
    require_once("db/conexao.php");

    $usuario = $_SESSION["usuario_id"];

    $stmt = $conn->prepare("SELECT * FROM estoque WHERE usuario_id = :usuario_id AND quantidade <= 10 ORDER BY quantidade ASC");
    
    $stmt->execute([':usuario_id' => $usuario]);

    $baixo = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <main  class="page-list">
        <h2 class="title-list">📊 Relatórios</h2>
        <p class="separador">Visualize os produtos com estoque baixo no estoque.</p>
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
                    <th><i class="fa-solid fa-circle-check"></i> Status</th>
                </tr>
                <?php foreach($baixo as $baixos): ?> 
                    <?php
                        if($baixos["quantidade"] <= 3) {
                            $icone = '🔴 Crítico';
                            $classe = "critico";
                        } else {
                            $icone = '🟡 Baixo';
                            $classe = "baixo";
                        };
                    ?>
                    <tr class="<?= $classe ?> table-cor">
                        <td><?= $baixos["nome"] ?></td>
                        <td><?= $baixos["marca"] ?></td>
                        <td><?= $baixos["descricao"] ?></td>
                        <td><?= $baixos["quantidade"] ?></td>
                        <td><?= 'R$ ' . number_format($baixos["preco"], 2, ',', '.') ?></td>
                        <td><?= $icone ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>