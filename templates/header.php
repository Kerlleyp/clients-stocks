<?php
    $paginaAtual = basename($_SERVER['PHP_SELF']);

    $paginasClientes = ['cliente.php', 'listar_clientes.php'];
    $paginasProdutos = ['estoque.php', 'listar_estoque.php'];
    $paginasCompras = ['compras.php', 'lista_compras.php'];
    $paginasRelatorios = ['relatorio_estoque_baixo.php', 'relatorio_vendidos.php'];
?>

<header>
    <div class="nav-container">
        <h1 class="title">🛒 Sistema de Vendas e Cadastro</h1>

        <nav>
            <!-- HOME -->
            <div class="menu">
                <a href="dashboard.php" class="<?= $paginaAtual == 'dashboard.php' ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i> Home
                </a>
            </div>

            <!-- CLIENTE -->
            <div class="menu">
                <a href="#" class="menu-link <?= in_array($paginaAtual, $paginasClientes) ? 'active' : '' ?>">
                    <span class="menu-text">
                        <i class="fa-solid fa-user"></i>
                        Cliente
                    </span>
                    <i class="fa-solid fa-chevron-down arrow-down"></i>
                </a>

                <div class="sub_links">
                    <a href="cliente.php" class="<?= $paginaAtual == 'cliente.php' ? 'active-sub' : '' ?>">
                        <i class="fa-solid fa-plus"></i>
                        Novo cliente
                    </a>

                    <a href="listar_clientes.php" class="<?= $paginaAtual == 'listar_clientes.php' ? 'active-sub' : '' ?>">
                        <i class="fa-solid fa-clipboard-list"></i>
                        Listar clientes
                    </a>
                </div>
            </div>

            <!-- PRODUTO -->
            <div class="menu">
                <a href="#" class="menu-link <?= in_array($paginaAtual, $paginasProdutos) ? 'active' : '' ?>">
                    <span class="menu-text">
                        <i class="fa-solid fa-box"></i>
                        Produto
                    </span>
                    <i class="fa-solid fa-chevron-down arrow-down"></i>
                </a>

                <div class="sub_links">
                    <a href="estoque.php" class="<?= $paginaAtual == 'estoque.php' ? 'active-sub' : '' ?>">
                        <i class="fa-solid fa-plus"></i>
                        Novo produto
                    </a>

                    <a href="listar_estoque.php" class="<?= $paginaAtual == 'listar_estoque.php' ? 'active-sub' : '' ?>">
                        <i class="fa-solid fa-clipboard-list"></i>
                        Listar produtos
                    </a>
                </div>
            </div>

            <!-- COMPRA -->
            <div class="menu">
                <a href="#" class="menu-link <?= in_array($paginaAtual, $paginasCompras) ? 'active' : '' ?>">
                    <span class="menu-text">
                        <i class="fa-solid fa-cart-plus"></i>
                        Compra
                    </span>
                    <i class="fa-solid fa-chevron-down arrow-down"></i>
                </a>

                <div class="sub_links">
                    <a href="compras.php" class="<?= $paginaAtual == 'compras.php' ? 'active-sub' : '' ?>">
                        <i class="fa-solid fa-plus"></i>
                        Nova compra
                    </a>

                    <a href="lista_compras.php" class="<?= $paginaAtual == 'lista_compras.php' ? 'active-sub' : '' ?>">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Histórico de compras
                    </a>
                </div>
            </div>

            <!-- RELATÓRIO -->
            <div class="menu">
                <a href="#" class="menu-link <?= in_array($paginaAtual, $paginasRelatorios) ? 'active' : '' ?>">
                    <span class="menu-text">
                        <i class="fa-solid fa-chart-column"></i>
                        Relatório
                    </span>
                    <i class="fa-solid fa-chevron-down arrow-down"></i>
                </a>

                <div class="sub_links">
                    <a href="relatorio_estoque_baixo.php" class="<?= $paginaAtual == 'relatorio_estoque_baixo.php' ? 'active-sub' : '' ?>">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Estoque baixo
                    </a>

                    <a href="relatorio_vendidos.php" class="<?= $paginaAtual == 'relatorio_vendidos.php' ? 'active-sub' : '' ?>">
                        <i class="fa-solid fa-chart-line"></i>
                        Mais vendidos
                    </a>
                </div>
            </div>

            <!-- SAIR -->
            <a href="logout.php" class="btn-sair-header">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Sair</span>
            </a>
        </nav>
    </div>
</header>