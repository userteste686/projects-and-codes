document.getElementById("corretorForm").addEventListener("submit", function(event) {
    let cpf = document.getElementById("cpf").value;
    let creci = document.getElementById("creci").value;
    let nome = document.getElementById("nome").value;

    if (cpf.length !== 11) {
        alert("O CPF deve conter exatamente 11 dígitos!");
        event.preventDefault();
    }
    if (creci.length < 2) {
        alert("O CRECI deve conter pelo menos 2 caracteres!");
        event.preventDefault();
    }
    if (nome.length < 2) {
        alert("O Nome deve conter pelo menos 2 caracteres!");
        event.preventDefault();
    }


});

function carregarCorretores() {
    fetch('listar.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById("tabelaCorretores").innerHTML = data;
        })
        .catch(error => console.error('Erro ao carregar a lista de corretores:', error));
}

// Chama a função ao carregar a página
window.onload = carregarCorretores;