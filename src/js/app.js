// Função para buscar produtos via AJAX
function buscarProdutos() {
    fetch("buscarProdutos.php")
        .then(response => response.json())
        .then(data => {
            const tabela = document.querySelector(".product-table tbody");
            tabela.innerHTML = "";
            data.forEach(produto => {
                const linha = `
                    <tr>
                        <td>${produto.codigo_produto}</td>
                        <td>${produto.descricao}</td>
                        <td>
                            <button onclick="editarProduto(${produto.codigo_produto})">Editar</button>
                            <button onclick="excluirProduto(${produto.codigo_produto})">Excluir</button>
                        </td>
                    </tr>`;
                tabela.insertAdjacentHTML("beforeend", linha);
            });
        });
}

// Função para excluir produto
function excluirProduto(codigo) {
    if (confirm("Deseja realmente excluir este produto?")) {
        fetch(`excluirProduto.php?codigo=${codigo}`, { method: "DELETE" })
            .then(response => response.text())
            .then(() => buscarProdutos());
    }
}

// Função para cadastrar ou atualizar
function salvarProduto(event) {
    event.preventDefault();
    const form = document.querySelector("#form");
    const dados = new FormData(form);

    fetch("salvarProduto.php", {
        method: "POST",
        body: dados,
    })
        .then(response => response.text())
        .then(() => {
            alert("Produto salvo com sucesso!");
            buscarProdutos();
        });
}
