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
                <input type="tel" name="phone" placeholder="Telefone" required>
                <input type="password" name="password" placeholder="Senha" required>
                <hr>
                <h3>Endereço de entrega</h3>
                <input type="text" name="cep" placeholder="CEP" required>
                <input type="text" name="street" placeholder="Rua" required>
                <input type="text" name="number" placeholder="Número" required>
                <input type="text" name="complement" placeholder="Complemento">
                <input type="text" name="district" placeholder="Bairro" required>
                <input type="text" name="city" placeholder="Cidade" required>
                <input type="text" name="state" placeholder="Estado" maxlength="2" required>
                <hr>
                <h3>Forma de pagamento</h3>
                <select name="payment" required>
                    <option value="">Selecione</option>
                    <option value="credito">Cartão de Crédito</option>
                    <option value="debito">Cartão de Débito</option>
                    <option value="pix">Pix</option>
                    <option value="dinheiro">Dinheiro</option>
                </select>
                <input type="text" name="card_name" placeholder="Nome impresso no cartão">
                <input type="text" name="card_number" placeholder="Número do cartão">
                <div class="linha">
                    <input type="text" name="expiry" placeholder="MM/AA">
                    <input type="text" name="cvv" placeholder="CVV">
                </div>
                <button type="submit">
                    Criar Conta
                </button>
            </form>

        </div>
    </section>
</main>

<?php require "includes/footer.php"; ?>