<?php require "includes/header.php"; ?>

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

    <section class="form-section" id="formulario">
        <div class="container" id="login">
            <h2>Sessão de login</h2>
            <form action="auth/processaLogin.php" method="POST" class="custom-form">
                <input type="email" name="email" placeholder="Seu e-mail" required>
                <input type="password" name="password" placeholder="Sua senha" required>
                <button type="submit">Logar</button>
            </form>
            <a class="btn-signup">Não possui conta?</a>
        </div>

        <div class="container" id="signup" hidden>
            <h2>Criar sua conta</h2>
            <form action="auth/processaSignup.php" method="POST" class="custom-form">
                <input type="text" name="name" placeholder="Nome completo" required>
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="password" name="password" placeholder="Senha" required>
                
                <button type="submit">
                    Criar Conta
                </button>
            </form>
        </div>
    </section>
</main>

<?php require "includes/footer.php"; ?>