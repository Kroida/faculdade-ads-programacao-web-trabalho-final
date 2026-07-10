<?php require "includes/header.php"; ?>
<?php require "auth/auth.php"; ?>

<main>
    <?php

    // Mensagens flash: são exibidas uma vez e removidas da sessão.
    if (isset($_SESSION["erro"])) {
        ?>

        <div class="alerta-erro">
            <p><?= htmlspecialchars($_SESSION["erro"]) ?></p>
        </div>

    <?php
        unset($_SESSION["erro"]);
    }
    ?>

    <?php

    // Exibe retorno positivo vindo de cadastro, update ou exclusão.
    if (isset($_SESSION["sucesso"])) {
        ?>

        <div class="alerta-sucesso">
            <p><?= htmlspecialchars($_SESSION["sucesso"]) ?></p>
        </div>

    <?php
        unset($_SESSION["sucesso"]);
    }
    ?>

    <section class="form-section">
        <div class="container">
            <h2>Olá, <?= htmlspecialchars($_SESSION["name"]) ?> 👋</h2>
            <form action="auth/processaUpdate.php" method="POST" enctype="multipart/form-data" class="custom-form">
                <div class="foto-perfil">
                    <img src="<?= htmlspecialchars($_SESSION["profile_image"]) ?>" alt="Foto do usuário"
                        id="preview-foto">
                    <label for="input-foto" class="btn-upload">Alterar foto</label>
                    <input type="file" id="input-foto" name="photo" accept="image/png,image/jpeg,image/webp" hidden>
                </div>
                <input type="text" name="name" placeholder="Nome completo"
                    value="<?= htmlspecialchars($_SESSION["name"]) ?>" required>
                <input type="email" name="email" placeholder="E-mail"
                    value="<?= htmlspecialchars($_SESSION["email"]) ?>" required>
                <input type="password" name="password" placeholder="Nova senha">

                <button id="btn-update" type="submit">Salvar alterações</button>
            </form>

            <?php

            // Usuários comuns podem excluir a própria conta; o admin acessa o painel.
            if (isset($_SESSION["id"]) && (int) ($_SESSION["id"]) !== 1) {
                ?>

                <a id="btn-delete" class="btn-opcao-dados">Excluir conta</a>

            <?php

            } else {
                ?>

                <a id="btn-admin" class="btn-opcao-dados" href="adm.php">Opções de administrador</a>

            <?php
            }
            ?>

        </div>
    </section>

    <dialog id="modal" class="modal-pedido">
        <div class="modal-header">
            <h2>Deletar conta</h2>
            <button type="button" id="fechar" class="btn-fechar">✕</button>
        </div>

        <form action="auth/processaDelete.php" method="POST" class="pedido-form">
            <div class="produto-info">
                <p id="modal-descricao">Tem certeza que deseja deletar sua conta?</p>
            </div>

            <div class="acoes">
                <button type="button" id="cancelar" class="btn-cancelar">Cancelar</button>
                <button type="submit" class="btn-confirmar">🗑️ Confirmar exclusão</button>
            </div>
        </form>
    </dialog>
</main>

<?php require "includes/footer.php"; ?>