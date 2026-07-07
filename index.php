<?php require "includes/header.php"; ?>

<main>
    <section class="section-destaque" id="destaque1">
        <div class="container-flex">
            <div class="destaque-texto">
                <span class="badge-tag">O Mais Pedido com 5 estrelas</span>
                <h2>BigUni</h2>
                <p>2 hamburgeres com alface, cebola, picles e pao com gergelim, e aquele molho especial da marca do
                    palhaço.</p>
                <div class="preco-box">
                    <span class="Preco-Hamburger">R$31,90</span>
                    <span class="Preco-Combo">37,90</span>
                </div>
                <a class="btn-comprar" data-id="biguni">Adicionar ao Pedido</a>
            </div>

            <div class="destaque-img1">
                <div class="img-placeholder"><img src="src/img/bigmac.jpg"></div>
            </div>
        </div>
    </section>

    <section class="section-destaque bg-light reverse" id="destaque-2">
        <div class="container-flex">
            <div class="destaque-texto">
                <span class="badge-tag">Melhor Custo-Beneficio</span>
                <h2>Box Casal Uni</h2>
                <p>A combinação perfeita para dividir: 2 hamburger Salada de forma caprichada, com porção de batata.
                </p>
                <div class="preco-casal">
                    <span class="preco-atual">R$ 54,90</span>
                </div>
                <a class="btn-comprar">Adicionar ao Pedido</a>
            </div>

            <div class="destaque-img1">
                <div class="img-placeholder"><img src="src/img/casal.webp"></div>
            </div>
        </div>
    </section>

    <section class="section-destaque" id="destaque-3">
        <div class="container-flex">
            <div class="destaque-texto">
                <span class="badge-tag">Melhor opção para os nossos pequenos</span>
                <h2>KidsUni</h2>
                <p>A opção perfeita para nossos pequenos, pão, carne e queijo com toque da nossa maionese especial.
                </p>
                <div class="preco-kids">
                    <span class="preco-atual">R$ 21,90</span>
                    <span class="Preco-Combo">28,90</span>
                </div>
                <a class="btn-comprar" data-id="kids">Adicionar ao Pedido</a>
            </div>

            <div class="destaque-img1">
                <div class="img-placeholder"><img src="src/img/Kids.jpg"></div>
            </div>
        </div>
    </section>

    <section class="section-destaque bg-light reverse" id="destaque-4">
        <div class="container-flex">
            <div class="destaque-texto">
                <span class="badge-tag">Melhor opção para o seu desjejum</span>
                <h2>MegaUltraUni</h2>
                <p>A opção perfeita para quebrar o seu jejum, com 4 carnes e muitoo queijo cheddar.</p>
                <div class="preco-Mega">
                    <span class="preco-atual">R$ 39,90</span>
                    <span class="Preco-Combo">43,90</span>
                </div>
                <a class="btn-comprar">Adicionar ao Pedido</a>
            </div>

            <div class="destaque-img1">
                <div class="img-placeholder"><img src="src/img/mega.jpg"></div>
            </div>
        </div>
    </section>

    <section class="section-destaque" id="destaque-5">
        <div class="container-flex">
            <div class="destaque-texto">
                <span class="badge-tag">Melhor opção de sobremesa</span>
                <h2>BrownieUni</h2>
                <p>A opção perfeita para uma sobremesa, pedacinhos de brownie com nossa calda de chocolate a parte.
                </p>
                <div class="preco-Brownie">
                    <span class="preco-atual">R$ 14,90</span>
                </div>
                <a class="btn-comprar">Adicionar ao Pedido</a>
            </div>

            <div class="destaque-img1">
                <div class="img-placeholder"><img src="src/img/brownie.jpg"></div>
            </div>
        </div>
    </section>

    <section class="pre-foot-section" id="formulario"></section>

    <dialog id="modal" class="modal-pedido">
        <div class="modal-header">
            <h2>Finalizar Pedido</h2>
            <button type="button" id="fechar" class="btn-fechar">✕</button>
        </div>

        <form class="pedido-form">
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
                <textarea id="observacao" name="observacao" rows="4"
                    placeholder="Ex.: sem cebola, molho à parte..."></textarea>
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
                    <option value="dinheiro">Dinheiro</option>
                </select>
                <input type="text" name="card_name" placeholder="Nome impresso no cartão">
                <input type="text" name="card_number" placeholder="Número do cartão">
                <input type="text" name="expiry" placeholder="MM/AA">
                <input type="text" name="cvv" placeholder="CVV">
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