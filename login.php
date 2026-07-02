<?php require "layout/header.php"; ?>

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

    <section class="form-section" id="formulario">
        <div class="container">
            <h2>Sessão de login</h2>
            <form action="processaLogin.php" method="POST" class="custom-form">
                <input type="email" name="email" placeholder="Seu e-mail" required>
                <input type="password" name="password" placeholder="Sua senha" required>
                <button type="submit">Logar</button>
            </form>
        </div>

        <div class="container">
            <h2>Sessão de cadastro</h2>

            <form action="processaSignup.php" method="POST" class="custom-form">
                <input type="text" name="name" placeholder="Seu nome" required>
                <input type="email" name="email" placeholder="Seu e-mail" required>
                <input type="password" name="password" placeholder="Sua senha" required>
                <button type="submit">Criar Conta</button>
            </form>
        </div>
    </section>
</main>

<?php require "layout/footer.php"; ?>