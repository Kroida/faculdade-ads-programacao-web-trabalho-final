document.addEventListener("DOMContentLoaded", function() {
    // Inicializa cada módulo separadamente
    initFotoPreview();
    initAuthToggle();
});

function initFotoPreview() {
    const inputFoto = document.getElementById("input-foto");
    const previewFoto = document.getElementById("preview-foto");

    if (!inputFoto || !previewFoto) {
        console.warn("Elementos de foto não encontrados");
        return;
    }

    inputFoto.addEventListener("change", (event) => {
        const arquivo = event.target.files[0];
        if (!arquivo) return;

        const leitor = new FileReader();
        leitor.onload = (e) => {
            previewFoto.src = e.target.result;
        };
        leitor.readAsDataURL(arquivo);
    });
}

function initAuthToggle() {
    const botaoSignup = document.querySelector(".btn-signup");
    const login = document.getElementById("login");
    const cadastro = document.getElementById("signup");

    if (!botaoSignup) {
        console.warn(".btn-signup não encontrado");
        return;
    }

    botaoSignup.addEventListener("click", (event) => {
        event.preventDefault();
        login.hidden = true;
        cadastro.hidden = false;
    });
}