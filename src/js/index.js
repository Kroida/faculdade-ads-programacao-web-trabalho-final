// Executa os módulos da página inicial somente depois que o HTML foi carregado.
document.addEventListener("DOMContentLoaded", function () {
    pedidoFeitoAlert();
    checkin();
    setupModalCloseButtons();
    mostrarOpcaoPagamento();
});

/**
 * Configura o modal de pedido para todos os botões "Adicionar ao Pedido".
 *
 * Os dados do produto vêm dos atributos data-* gerados dinamicamente pelo PHP.
 * Assim, quando o administrador muda uma section destaque no banco, o modal já
 * passa a usar os novos valores sem precisar alterar o JavaScript.
 */
function checkin() {
    const modal = document.getElementById("modal");
    const modalNome = document.getElementById("modal-nome");
    const modalDescricao = document.getElementById("modal-descricao");
    const modalPreco = document.getElementById("modal-preco");
    const modalPrecoTotal = document.getElementById("modal-preco-total");
    const inputQuantidade = document.getElementById("quantidade");

    if (!modal || !inputQuantidade) {
        return;
    }

    let produtoAtual = null;

    inputQuantidade.addEventListener("input", () => {
        checkinAtualizarTotal(produtoAtual, modalPrecoTotal);
    });

    const botoes = document.querySelectorAll(".btn-comprar");

    botoes.forEach(botao => {
        botao.addEventListener("click", () => {
            const preco = Number(botao.dataset.preco || 0);
            const combo = botao.dataset.combo ? Number(botao.dataset.combo) : null;

            produtoAtual = {
                nome: botao.dataset.nome || "Produto",
                descricao: botao.dataset.descricao || "",
                preco: preco,
                combo: combo
            };

            inputQuantidade.value = 1;

            modalNome.textContent = produtoAtual.nome;
            modalDescricao.textContent = produtoAtual.descricao;
            modalPreco.textContent = formatarPreco(produtoAtual.preco);

            checkinAtualizarTotal(produtoAtual, modalPrecoTotal);

            modal.showModal();
        });
    });
}

/**
 * Recalcula o valor total do pedido no modal.
 *
 * Se o produto tiver preço de combo e a quantidade for 2, o total usa o valor do
 * combo. Caso contrário, multiplica o preço unitário pela quantidade escolhida.
 */
function checkinAtualizarTotal(produtoAtual, modalPrecoTotal) {
    if (!produtoAtual || !modalPrecoTotal) {
        return;
    }

    const quantidade = Number(document.getElementById("quantidade").value || 1);
    let total = produtoAtual.preco * quantidade;

    if (produtoAtual.combo && quantidade === 2) {
        total = produtoAtual.combo;
    }

    modalPrecoTotal.textContent = formatarPreco(total);
}

/**
 * Formata valores numéricos em moeda brasileira.
 */
function formatarPreco(valor) {
    return valor.toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL"
    });
}

/**
 * Configura os botões de fechar e cancelar do modal de pedido.
 */
function setupModalCloseButtons() {
    const modal = document.getElementById("modal");
    const botaoFechar = document.getElementById("fechar");
    const botaoCancelar = document.getElementById("cancelar");

    if (!modal || !botaoFechar || !botaoCancelar) {
        return;
    }

    botaoFechar.addEventListener("click", () => {
        modal.close();
    });

    botaoCancelar.addEventListener("click", () => {
        modal.close();
    });
}

/**
 * Exibe ou esconde os campos de cartão conforme a forma de pagamento escolhida.
 */
function mostrarOpcaoPagamento() {
    const selectPagamento = document.querySelector("select[name='payment']");
    const camposCartao = document.querySelectorAll(".input-cartao");

    if (!selectPagamento) {
        return;
    }

    selectPagamento.addEventListener("change", (event) => {
        const opcao = event.target.value;

        camposCartao.forEach(campo => {
            campo.hidden = !(opcao === "credito" || opcao === "debito");
        });
    });
}

/**
 * Mostra um alerta simples quando o pedido foi finalizado.
 *
 * A variável `pedidoFeito` é impressa pelo PHP no footer para informar ao JS que
 * o pedido acabou de ser concluído.
 */
function pedidoFeitoAlert() {
    if (typeof pedidoFeito !== "undefined" && pedidoFeito === true) {
        alert("Obrigado pela compra! Recibo enviado por email.");
    }
}
