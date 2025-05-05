'use strict';

// const myForm = document.getElementById("form");

// myForm.addEventListener('submit', gravar);

// function gravar(e) {
//     e.preventDefault(); // Previne o comportamento padrão do formulário

//     const formData = new FormData(myForm);

//     fetch('valida.php', {
//         method: 'POST',
//         body: formData
//     }).then(response => response.json())
//     .then(data => {
//         if (data.status === "sucesso") {
//             alert(data.message);
//             myForm.reset();
//         } else {
//             alert(data.message);
//         }
//     }).catch(function(error) {
//         console.error(error);
//     });
// }




document.addEventListener("DOMContentLoaded", function() {
    
    const btnSalvarList = document.querySelectorAll(".btn-salvar");
    btnSalvarList.forEach(btn => {
        btn.addEventListener("click", function() {
            const containerTeste = this.closest(".container-teste");
            const codigoProduto = containerTeste.querySelector("[name='codigo_produto']").value;
            const descricao = containerTeste.querySelector("[name='descricao']").value;
            const qtde = containerTeste.querySelector("[name='qtde']").value;

    
            fetch('atualizarProduto.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    codigo_produto: codigoProduto,
                    descricao: descricao,
                    qtde: qtde
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert(data.message);
                } else {
                    alert("Erro: " + data.message);
                }
            })
            .catch(error => {
                console.error("Erro na atualização:", error);
            });
        });
    });
});




function searchProduct() {

    const searchValue = document.getElementById('search-input').value.toLowerCase();
    const tableRows = document.querySelectorAll('.product-table tbody tr');

    
    tableRows.forEach(row => {
      
        const codigoProduto = row.cells[0].textContent.toLowerCase();
        const nomeProduto = row.cells[1].textContent.toLowerCase();

        
        if (codigoProduto.includes(searchValue) || nomeProduto.includes(searchValue)) {
            row.style.display = ''; 
        } else {
            row.style.display = 'none'; 
        }
    });
}

function toggleCadastroForm() {
    const form = document.getElementById('cad');
    form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
    form.classList.toggle('active', form.style.display === 'block');
}

function fecharCadastroForm() {
    const form = document.getElementById('cad');
    form.style.display = 'none';
    form.classList.remove('active');
}

