<?php

    require_once("db/conexao.php");

    $stmt = $conn->query('SELECT * FROM estoque WHERE quantidade <= 10 ORDER BY quantidade ASC');

    $baixo = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatorio Baixo</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('templates/header.php'); ?>
     <main>
        <!--Mostra os Produtos em Baixa-->
        <table class="table-container">
            <tr id="color-estoque">
                <th>Nome</th>
                <th>Marca</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Status</th>
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
                <tr class="<?= $classe ?>">
                    <td><?= $baixos["nome"] ?></td>
                    <td><?= $baixos["marca"] ?></td>
                    <td><?= $baixos["descricao"] ?></td>
                    <td><?= $baixos["quantidade"] ?></td>
                    <td><?= 'R$ ' . number_format($baixos["preco"], 2, ',', '.') ?></td>
                    <td><?= $icone ?></td>
                </tr>
            <?php endforeach; ?> 
        </table>
    </main>
    <?php require_once('templates/footer.php'); ?>
</body>
</html>