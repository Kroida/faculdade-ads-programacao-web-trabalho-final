document.addEventListener("DOMContentLoaded", function () {
    pedidoFeitoAlert();
    checkin();
    setupModalCloseButtons();
    mostrarOpcaoPagamento();
});

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

function formatarPreco(valor) {
    return valor.toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL"
    });
}

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

function pedidoFeitoAlert() {
    if (typeof pedidoFeito !== "undefined" && pedidoFeito === true) {
        alert("Obrigado pela compra! Recibo enviado por email.");
    }
}
