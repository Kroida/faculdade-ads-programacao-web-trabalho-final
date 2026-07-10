<?php
// Controla se o JavaScript deve mostrar o alerta de pedido finalizado.
$pedidoFeito = false;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["pedidofeito"])) {
    $pedidoFeito = true;
    unset($_SESSION["pedidofeito"]);
}

require_once "config/admFunctions.php";

// Busca somente as sections ativas; o conteúdo é gerenciado no painel adm.php.
$destaques = listDestaques($pdo, true);

require "includes/header.php";
?>

<main>
    <section class="banner" id="banner">
        <div class="container">
            <img src="src/img/logo.jpg" alt="Logo UniFood">

            <p>Pediu? Chegou!</p>
        </div>
    </section>

    <?php // Renderiza dinamicamente cada section destaque cadastrada no banco. ?>
    <?php foreach ($destaques as $destaque): ?>
        <section class="<?= htmlspecialchars(classesDestaque($destaque)) ?>" id="<?= htmlspecialchars($destaque["html_id"]) ?>">
            <div class="container-flex">
                <div class="destaque-texto">
                    <span class="badge-tag"><?= htmlspecialchars($destaque["badge_text"]) ?></span>

                    <h2><?= htmlspecialchars($destaque["title"]) ?></h2>

                    <p><?= htmlspecialchars($destaque["description"]) ?></p>

                    <div class="preco-box">
                        <span class="preco-atual"><?= htmlspecialchars(formatarPrecoBr($destaque["price"])) ?></span>

                        <?php if (!empty($destaque["combo_price"])): ?>
                            <span class="Preco-Combo"><?= htmlspecialchars(formatarPrecoBr($destaque["combo_price"])) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Os atributos data-* alimentam o modal de pedido no src/js/index.js. -->
                    <a
                        class="btn-comprar"
                        data-id="<?= htmlspecialchars($destaque["product_key"]) ?>"
                        data-nome="<?= htmlspecialchars($destaque["title"]) ?>"
                        data-descricao="<?= htmlspecialchars($destaque["description"]) ?>"
                        data-preco="<?= htmlspecialchars($destaque["price"]) ?>"
                        data-combo="<?= htmlspecialchars($destaque["combo_price"] ?? "") ?>"
                    >
                        Adicionar ao Pedido
                    </a>
                </div>

                <div class="destaque-img1">
                    <div class="img-placeholder">
                        <img src="<?= htmlspecialchars($destaque["image_path"]) ?>" alt="<?= htmlspecialchars($destaque["image_alt"]) ?>">
                    </div>
                </div>
            </div>
        </section>
    <?php endforeach; ?>

    <section class="pre-foot-section" id="formulario"></section>

    <dialog id="modal" class="modal-pedido">
        <div class="modal-header">
            <h2>Finalizar Pedido</h2>
            <button type="button" id="fechar" class="btn-fechar">✕</button>
        </div>

        <form action="auth/authPedido.php" method="POST" class="pedido-form">
            <div class="produto-info">
                <h3 id="modal-nome"></h3>
                <p id="modal-descricao"></p>
            </div>

            <div class="campo">
                <label for="quantidade">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" min="1" value="1">
            </div>

            <div class="campo">
                <label for="observacao">Alguma observação?</label>
                <textarea id="observacao" name="observacao" rows="4" placeholder="Ex.: sem cebola, molho à parte..."></textarea>
            </div>

            <div class="campo">
                <label>Telefone</label>
                <input type="tel" name="phone" placeholder="Telefone" required>
            </div>

            <div class="campo">
                <label>Endereço de entrega</label>
                <input type="text" name="cep" placeholder="CEP" required>
                <input type="text" name="street" placeholder="Rua" required>
                <input type="text" name="number" placeholder="Número" required>
                <input type="text" name="complement" placeholder="Complemento">
                <input type="text" name="district" placeholder="Bairro" required>
                <input type="text" name="city" placeholder="Cidade" required>
                <input type="text" name="state" placeholder="Estado" maxlength="2" required>
            </div>

            <div class="campo">
                <label>Forma de pagamento</label>
                <select name="payment" required>
                    <option value="">Selecione</option>
                    <option value="credito">Cartão de Crédito</option>
                    <option value="debito">Cartão de Débito</option>
                    <option value="pix">Pix</option>
                </select>
                <input class="input-cartao" type="text" name="card_name" placeholder="Nome impresso no cartão" hidden>
                <input class="input-cartao" type="text" name="card_number" placeholder="Número do cartão" hidden>
                <input class="input-cartao" type="text" name="expiry" placeholder="MM/AA" hidden>
                <input class="input-cartao" type="text" name="cvv" placeholder="CVV" hidden>
            </div>

            <div class="resumo-pedido">
                <div>
                    <span>Produto</span>
                    <strong id="modal-preco"></strong>
                </div>

                <div>
                    <span>Entrega</span>
                    <strong>Grátis</strong>
                </div>

                <hr>

                <div class="total">
                    <span>Total</span>
                    <strong id="modal-preco-total"></strong>
                </div>
            </div>

            <div class="acoes">
                <button type="button" id="cancelar" class="btn-cancelar">Cancelar</button>
                <button type="submit" class="btn-confirmar">🛒 Fechar pedido</button>
            </div>
        </form>
    </dialog>
</main>

<?php require "includes/footer.php"; ?>
