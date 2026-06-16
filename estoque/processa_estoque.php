<?php
    session_start();
    require_once("../db/conexao.php");

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = $_POST['nome'];
        $marca = $_POST['marca'];
        $descricao = $_POST['descricao'];
        $quantidade = $_POST['quantidade'];
        $preco = $_POST['preco'];
        $usuario_id = $_SESSION['usuario_id'];

        // Verifica se os campos estão vazios
        if(empty($nome) || empty($marca) || empty($descricao) || empty($quantidade) || empty($preco)) {

            $_SESSION['error'] = "Preencha todos os campos!";

        } else {

            try {

                $stmt = $conn->prepare("INSERT INTO estoque (nome, descricao, quantidade, preco, marca, usuario_id) VALUES (:nome, :descricao, :quantidade, :preco, :marca, :usuario_id)");

                $stmt->bindParam(":nome", $nome);
                $stmt->bindParam(":descricao", $descricao);
                $stmt->bindParam(":quantidade", $quantidade);
                $stmt->bindParam(":preco", $preco);
                $stmt->bindParam(":marca", $marca);
                $stmt->bindParam(":usuario_id", $usuario_id);

                $resultado = $stmt->execute();

                if($resultado) {
                    $_SESSION['success'] = "Produto cadastrado com sucesso!";
                } else {
                    $_SESSION['error'] = "Erro ao cadastrar o Produto!";
                }

            } catch(PDOException $e) {

                $_SESSION['error'] = "Erro no banco: " . $e->getMessage();

            }

        }

    } else {

        $_SESSION['error'] = "Requisição inválida!";

    }

    header("Location: ../estoque.php");
    exit;
?>