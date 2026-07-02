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
const modalCombo = null;
const modalPrecoTotal = document.getElementById("modal-preco-total");
const inputQuantidade = document.getElementById("quantidade");

inputQuantidade.addEventListener("input", atualizarTotal);

const botoes = document.querySelectorAll(".btn-comprar");

let produtoAtual = null;

botoes.forEach(botao => {

    botao.addEventListener("click", () => {

        const id = botao.dataset.id;

        produtoAtual = produtos[id];

        document.getElementById("quantidade").value = 1;

        modalNome.textContent = produtoAtual.nome;
        modalDescricao.textContent = produtoAtual.descricao;
        modalPreco.textContent = `R$ ${produtoAtual.preco.toFixed(2)}`;


        atualizarTotal();

        modal.showModal();

    });

});

function atualizarTotal() {

    const quantidade = Number(document.getElementById("quantidade").value);

    const total = produtoAtual.preco * quantidade;

    if (quantidade == 2 && produtoAtual.combo) {
        modalPrecoTotal.textContent = `R$ ${produtoAtual.combo.toFixed(2)}`;
    } else {
        modalPrecoTotal.textContent = `R$ ${total.toFixed(2)}`;
    }

}

document.getElementById("fechar").addEventListener("click", () => {
    modal.close();
});