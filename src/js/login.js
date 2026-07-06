const login = document.getElementById("login")
const cadastro = document.getElementById("signup");
const botao = document.querySelector(".btn-signup");

botao.addEventListener("click", (event) => {
    event.preventDefault();
    hide();
})

function hide() {
    login.hidden = true;
    cadastro.hidden = false;
}