<?php
    session_start();
    require_once("../db/conexao.php");

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = trim($_POST['nome']);
        $telefone = trim($_POST['telefone']);
        $endereco = trim($_POST['endereco']);
        $usuario_id = $_SESSION['usuario_id'];

        // Verifica se os campos estão vazios
        if(empty($nome) || empty($telefone) || empty($endereco)) {

            $_SESSION['error'] = "Preencha todos os campos!";

        } else {

            try {

                $stmt = $conn->prepare("
                    INSERT INTO clientes (nome, telefone, endereco, usuario_id)
                    VALUES (:nome, :telefone, :endereco, :usuario_id)
                ");

                $stmt->bindParam(":nome", $nome);
                $stmt->bindParam(":telefone", $telefone);
                $stmt->bindParam(":endereco", $endereco);
                $stmt->bindParam(":usuario_id", $usuario_id);

                $resultado = $stmt->execute();

                if($resultado) {
                    $_SESSION['success'] = "Cliente cadastrado com sucesso!";
                } else {
                    $_SESSION['error'] = "Erro ao cadastrar cliente!";
                }

            } catch(PDOException $e) {

                $_SESSION['error'] = "Erro no banco: " . $e->getMessage();

            }

        }

    } else {

        $_SESSION['error'] = "Requisição inválida!";

    }

    header("Location: ../cliente.php");
    exit;
?>