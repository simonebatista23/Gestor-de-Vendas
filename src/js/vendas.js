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



function excluirCliente(id) {
    if (confirm("Tem certeza que deseja excluir o produto?")) {
       
        fetch('excluirCliente.php', {
            method: 'POST',
            body: new URLSearchParams({
                id: id
            })
        })
        .then(response => {
            if (response.ok) {
                
                alert('Produto excluído com sucesso!');
                location.reload(); 
            } else {
               
                alert('Erro ao excluir produto.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir produto. Tente novamente.');
        });
    }
}


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

document.getElementById('form').addEventListener('submit', async function (e) {
    e.preventDefault(); // Impede o envio tradicional do formulário

    // Obtém os dados do formulário
    const formData = new FormData(this);

    try {
        // Faz a requisição POST para o validaCliente.php
        const response = await fetch('validaCliente.php', {
            method: 'POST',
            body: formData,
        });

        // Verifica se a resposta está OK (HTTP 200)
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }

        // Converte a resposta para JSON
        const result = await response.json();

        // Verifica o status retornado pelo validaCliente.php
        if (result.status === 'success') { // Corrigido para 'success' em vez de 'sucesso'
            alert(result.message);
            
            // Atualiza a página automaticamente após 500ms
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            alert('Erro: ' + result.message);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao processar o cadastro.');
    }
});

