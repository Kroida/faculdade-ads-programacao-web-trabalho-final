const inputFoto = document.getElementById("input-foto");
const previewFoto = document.getElementById("preview-foto");
const botao = document.getElementById("btn-update");

inputFoto.addEventListener("change", function(event) {
    const arquivo = event.target.files[0];
    
    if (!arquivo) {
        console.log("Nenhum arquivo selecionado");
        return;
    }
    
    const leitor = new FileReader();
    
    leitor.onload = function(e) {
        previewFoto.src = e.target.result;
        console.log("Preview atualizado!");
    };
    
    leitor.readAsDataURL(arquivo);
});