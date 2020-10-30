const texto = document.getElementById("texto");
const btnCopiador = document.getElementById("btnCopiador");

btnCopiador.addEventListener("click", function() {
    texto.select();
    document.execCommand("Copy");
});