<?php   
    session_start();
    require_once("../db/conexao.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    //Pegar Cliente 
    $stmt = $conn->prepare("SELECT * FROM compras WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $compra = $stmt->fetch();
    $cliente_id = $compra['cliente_id'];
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = :cliente_id");
    $stmt->bindParam(":cliente_id", $cliente_id);
    $stmt->execute();
    $cliente = $stmt->fetch();
    $nome_cliente = $cliente['nome'];

    // Deleta os itens da compra
    $stmt = $conn->prepare("DELETE FROM itens_compra WHERE compra_id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    // Deleta a compra
    $stmt = $conn->prepare("DELETE FROM compras WHERE id = :id");
    $stmt->bindParam(":id", $id);
    if($stmt->execute()) {
        $_SESSION['success'] = "Compra do(a) $nome_cliente excluída com sucesso!";
    } else {
         $_SESSION['error'] = "Erro ao excluir compra do(a) $nome_cliente!";
    }
}

header("Location: ../lista_compras.php");
exit;