<?php
session_start();
// Usa a mesma conexão do seu banco de dados que você já configurou
require_once("../db/conexao.php");

// 1. Verificar se o código de recuperação veio na URL do e-mail
if (!isset($_GET['codigo']) || empty($_GET['codigo'])) {
    die("<p style='color: red; text-align: center; font-family: sans-serif; margin-top: 50px;'>Código de recuperação inválido ou ausente!</p>");
}

$codigoURL = $_GET['codigo'];

// 2. Verificar se o código existe na sua tabela e ainda é válido por horário
$stmt = $conn->prepare("SELECT * FROM recuperacao_senha WHERE codigo = :codigo AND expira_em > NOW()");
$stmt->bindParam(":codigo", $codigoURL);
$stmt->execute();
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    die("<p style='color: red; text-align: center; font-family: sans-serif; margin-top: 50px;'>Este link ou código de recuperação expirou! Solicite um novo link na tela de login.</p>");
}

// 3. Se o formulário com a nova senha for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novaSenha = $_POST['nova_senha'];
    $confirmSenha = $_POST['confirmar_senha'];

    if ($novaSenha !== $confirmSenha) {
        $erro = "As novas senhas não conferem!";
    } else {
        // Criptografa a nova senha com o mesmo padrão seguro que você já usa
        $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $usuario_id = $pedido['usuario_id'];

        // Atualiza a senha na tabela usuarios usando o usuario_id associado ao código
        $stmtUpdate = $conn->prepare("UPDATE usuarios SET senha = :senha WHERE id = :usuario_id");
        $stmtUpdate->bindParam(":senha", $novaSenhaHash);
        $stmtUpdate->bindParam(":usuario_id", $usuario_id);
        $stmtUpdate->execute();

        // Apaga o código usado da tabela para ele nunca mais ser reutilizado por segurança
        $stmtDelete = $conn->prepare("DELETE FROM recuperacao_senha WHERE codigo = :codigo");
        $stmtDelete->bindParam(":codigo", $codigoURL);
        $stmtDelete->execute();

        // Define a mensagem de sucesso que você costuma usar nas sessões
        $_SESSION['success'] = "Senha redefinida com sucesso! Você já pode fazer o login.";
        
        // Redireciona para a sua tela de login (Ajuste o caminho se sua tela tiver outro nome)
        header("Location: ../index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definir Nova Senha</title>
</head>
<body>

<div class="card">
    <h2>Cadastrar Nova Senha</h2>
    <p>Digite sua nova senha de acesso abaixo:</p>
    
    <?php if (isset($erro)): ?>
        <div class="erro"><?= $erro ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Nova Senha:</label>
        <input type="password" name="nova_senha" required placeholder="Digite a nova senha">

        <label>Confirme a Nova Senha:</label>
        <input type="password" name="confirmar_senha" required placeholder="Repita a nova senha">

        <button type="submit">Atualizar Senha</button>
    </form>
</div>

</body>
</html>
