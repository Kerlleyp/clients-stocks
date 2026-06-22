<?php
    session_start();
    require_once("db/conexao.php");

    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :usuario_id");
    $stmt->bindParam(":usuario_id", $usuario_id);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $data = new DateTime($usuario["created_at"]);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('templates/header.php'); ?>

    <main class="page">
        <section class="perfil-container">

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
            <div class="perfil-topo">
                <h1>Meu Perfil</h1>
                <p>Gerencie suas informações pessoais e de segurança.</p>
            </div>

            <!-- CARD 1 - DADOS DO PERFIL -->
            <div class="perfil-card">
                <div class="card-header">
                    <div class="card-icone">👤</div>
                    <div>
                        <h2>Dados do Perfil</h2>
                        <p>Atualize suas informações pessoais.</p>
                    </div>
                </div>

                <form action="perfil/atualizar_cadastro.php" method="POST" class="form-perfil">
                    <div class="form-grid">
                        <div class="form-grupo">
                            <label for="nome">Nome</label>
                            <input 
                                type="text" 
                                name="nome" 
                                id="nome" 
                                value="<?= htmlspecialchars($usuario['nome']) ?>"
                            >
                        </div>

                        <div class="form-grupo">
                            <label for="email">Email</label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="<?= htmlspecialchars($usuario['email']) ?>"
                            >
                        </div>
                    </div>

                    <div class="form-grupo">
                        <label for="data_cadastro">Data do Cadastro</label>
                        <input 
                            type="text" 
                            id="data_cadastro"
                            value="<?= $data->format('d/m/Y'); ?>"
                            readonly
                        >
                    </div>

                    <div class="info-box">
                        A data de cadastro não pode ser alterada.
                    </div>

                    <div class="acoes-form">
                        <button type="submit" name="atualizar_perfil" class="btn-perfil">
                            Atualizar Perfil
                        </button>
                    </div>
                </form>
            </div>

            <!-- CARD 2 - SEGURANÇA -->
            <div class="perfil-card">
                <div class="card-header">
                    <div class="card-icone seguranca">🔒</div>
                    <div>
                        <h2>Segurança</h2>
                        <p>Altere sua senha para manter sua conta segura.</p>
                    </div>
                </div>

                <form action="perfil/alterar_senha.php" method="POST" class="form-senha">
                    <div class="form-grupo">
                        <label for="senha_atual">Senha atual</label>
                        <input 
                            type="password" 
                            name="senha_atual" 
                            id="senha_atual" 
                            placeholder="Digite sua senha atual"
                        >
                    </div>

                    <div class="form-grid">
                        <div class="form-grupo">
                            <label for="nova_senha">Nova senha</label>
                            <input 
                                type="password" 
                                name="nova_senha" 
                                id="nova_senha" 
                                placeholder="Digite sua nova senha"
                            >
                        </div>

                        <div class="form-grupo">
                            <label for="confirmar_senha">Confirmar nova senha</label>
                            <input 
                                type="password" 
                                name="confirmar_senha" 
                                id="confirmar_senha" 
                                placeholder="Confirme sua nova senha"
                            >
                        </div>
                    </div>

                    <div class="info-box info-sucesso">
                        Use pelo menos 6 caracteres com letras e números para uma senha mais segura.
                    </div>

                    <div class="acoes-form">
                        <button type="submit" name="alterar_senha" class="btn-perfil">
                            Alterar Senha
                        </button>
                    </div>
                </form>
            </div>

        </section>
    </main>

    <?php require_once('templates/footer.php'); ?>
</body>
</html>