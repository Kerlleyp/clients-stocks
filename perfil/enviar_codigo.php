<?php
session_start();
require '../vendor/autoload.php';
// Adiciona sua conexão oficial aqui também!
require_once("../db/conexao.php"); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailUsuario = $_POST['email'];

    try {
        // 1. Buscar o ID do usuário baseado no e-mail digitado usando a sua variável $conn
        $stmtUser = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmtUser->bindParam(":email", $emailUsuario);
        $stmtUser->execute();
        $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            die("<p style='color: red;'>E-mail não encontrado no sistema!</p>");
        }

        $usuarioId = $usuario['id'];

        // 2. Criar um código numérico de 6 dígitos seguro e definir a expiração (1 hora)
        $codigo = random_int(100000, 999999);
        $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // 3. CORRIGIDO: Salvando na sua tabela usando a variável correta ($conn) e o padrão do seu sistema
        $stmtInsert = $conn->prepare("INSERT INTO recuperacao_senha (usuario_id, codigo, expira_em) VALUES (:usuario_id, :codigo, :expira_em)");
        $stmtInsert->bindParam(":usuario_id", $usuarioId);
        $stmtInsert->bindParam(":codigo", $codigo);
        $stmtInsert->bindParam(":expira_em", $expiracao);
        $stmtInsert->execute();

        // 4. Configurar e enviar o e-mail com o PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = '9a73dc95e29d61';            
        $mail->Password   = 'e859347cb85182';        
        $mail->Port       = 2525;
        $mail->CharSet    = 'UTF-8';

        // Remetente e Destinatário
        $mail->setFrom('sistema@clientsstocks.com', 'Clients Stocks');
        $mail->addAddress($emailUsuario);

        // Link dinâmico apontando para o seu novo arquivo com o código na URL
        $linkRecuperacao = "http://localhost/clients-stocks/perfil/recuperar_senha_form.php?codigo=" . $codigo;

        // Visual do E-mail
        $mail->isHTML(true);
        $mail->Subject = 'Recuperação de Senha - Clients Stocks';
        $mail->Body    = "<h2>Olá!</h2>
                          <p>Você solicitou a troca de sua senha no sistema.</p>
                          <p>Seu código de verificação é: <strong>{$codigo}</strong></p>
                          <p>Clique no link abaixo para cadastrar uma nova senha:</p>
                          <p><a href='{$linkRecuperacao}' style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block;'>Redefinir Senha</a></p>
                          <p>Se não foi você quem pediu, ignore este e-mail.</p>";

        $mail->send();
        echo "<p style='color: green;'>Sucesso! O e-mail de recuperação foi enviado para a sua caixa.</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Erro: {$e->getMessage()}</p>";
    }
}
?>
