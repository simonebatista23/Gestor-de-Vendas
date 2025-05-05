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





