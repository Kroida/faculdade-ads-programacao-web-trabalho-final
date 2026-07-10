// Executa os módulos das telas de login/dados do cliente depois do carregamento.
document.addEventListener("DOMContentLoaded", function () {
    initFotoPreview();
    initAuthToggle();
    deleteAccount();
    setupModalCloseButtons();
});

/**
 * Mostra uma prévia da foto escolhida antes do envio do formulário.
 *
 * FileReader converte a imagem local para uma URL temporária em base64, permitindo
 * atualizar o `src` da imagem sem fazer upload imediato.
 */
function initFotoPreview() {
    const inputFoto = document.getElementById("input-foto");
    const previewFoto = document.getElementById("preview-foto");

    if (!inputFoto || !previewFoto) {
        return;
    }

    inputFoto.addEventListener("change", (event) => {
        const arquivo = event.target.files[0];

        if (!arquivo) {
            return;
        }

        const leitor = new FileReader();

        leitor.onload = (e) => {
            previewFoto.src = e.target.result;
        };

        leitor.readAsDataURL(arquivo);
    });
}

/**
 * Alterna a tela de login para o formulário de cadastro.
 */
function initAuthToggle() {
    const botaoSignup = document.querySelector(".btn-signup");
    const login = document.getElementById("login");
    const cadastro = document.getElementById("signup");

    if (!botaoSignup || !login || !cadastro) {
        return;
    }

    botaoSignup.addEventListener("click", (event) => {
        event.preventDefault();
        login.hidden = true;
        cadastro.hidden = false;
    });
}

/**
 * Abre o modal de confirmação de exclusão de conta.
 */
function deleteAccount() {
    const botaoDelete = document.getElementById("btn-delete");
    const modal = document.getElementById("modal");

    if (!botaoDelete || !modal) {
        return;
    }

    botaoDelete.addEventListener("click", (event) => {
        event.preventDefault();
        modal.showModal();
    });
}

/**
 * Configura os botões de fechar e cancelar do modal de exclusão.
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
