<?php require "includes/header.php"; ?>

<main>
    <?php

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
                    <img src="<?= htmlspecialchars($_SESSION["profile_image"]) ?>" alt="Foto do usuário" id="preview-foto">
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
        </div>
    </section>
</main>

<?php require "includes/footer.php"; ?>