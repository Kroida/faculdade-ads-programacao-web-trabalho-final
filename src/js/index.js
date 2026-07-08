document.addEventListener("DOMContentLoaded", function () {
    pedidoFeitoAlert();
    pedidos();
    setupModalCloseButtons();
    mostrarOpcaoPagamento(); // ✅ Deixa por último
});

function pedidos() {
    const produtos = {
        biguni: {
            nome: "BigUni",
            preco: 31.90,
            combo: 37.90,
            descricao: "2 hamburgeres com alface, cebola, picles e pao com gergelim, e aquele molho especial da marca do palhaço."
        },
        kids: {
            nome: "KidsUni",
            preco: 21.90,
            combo: 28.90,
            descricao: "A opção perfeita para nossos pequenos, pão, carne e queijo com toque da nossa maionese especial."
        }
    };

    const modal = document.getElementById("modal");
    const modalNome = document.getElementById("modal-nome");
    const modalDescricao = document.getElementById("modal-descricao");
    const modalPreco = document.getElementById("modal-preco");
    const modalPrecoTotal = document.getElementById("modal-preco-total");
    const inputQuantidade = document.getElementById("quantidade");

    let produtoAtual = null;

    inputQuantidade.addEventListener("input", () => {
        pedidosAtualizarTotal(produtoAtual, modalPrecoTotal);
    });

    const botoes = document.querySelectorAll(".btn-comprar");
    botoes.forEach(botao => {
        botao.addEventListener("click", () => {
            const id = botao.dataset.id;
            produtoAtual = produtos[id];

            document.getElementById("quantidade").value = 1;

            modalNome.textContent = produtoAtual.nome;
            modalDescricao.textContent = produtoAtual.descricao;
            modalPreco.textContent = `R$ ${produtoAtual.preco.toFixed(2)}`;

            pedidosAtualizarTotal(produtoAtual, modalPrecoTotal);

            modal.showModal();
        });
    });
}

function pedidosAtualizarTotal(produtoAtual, modalPrecoTotal) {
    const quantidade = Number(document.getElementById("quantidade").value);
    let total = produtoAtual.preco * quantidade;

    if (produtoAtual.combo && quantidade === 2) {
        total = produtoAtual.combo;
    }

    modalPrecoTotal.textContent = `R$ ${total.toFixed(2)}`;
}

function setupModalCloseButtons() {
    const modal = document.getElementById("modal");
    const botaoFechar = document.getElementById("fechar");
    const botaoCancelar = document.getElementById("cancelar");

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

    // Evento ao mudar a opção de pagamento
    selectPagamento.addEventListener("change", (event) => {
        const opcao = event.target.value;

        if (opcao === "credito" || opcao === "debito") {
            camposCartao.forEach(campo => {
                campo.hidden = false;
            });
        } else {
            camposCartao.forEach(campo => {
                campo.hidden = true;
            });
        }
    });
}

function pedidoFeitoAlert() {
    if (typeof pedidoFeito !== 'undefined' && pedidoFeito === true) {
        alert('Obrigado pela compra! Recibo enviado por email.');
    }
}