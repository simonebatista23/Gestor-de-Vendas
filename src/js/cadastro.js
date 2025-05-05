document.getElementById('form').addEventListener('submit', async function (e) {
    e.preventDefault(); // Impede o envio tradicional do formulário

    // Obtém os dados do formulário
    const formData = new FormData(this);

    try {
        // Faz a requisição POST para o valida.php
        const response = await fetch('valida.php', {
            method: 'POST',
            body: formData,
        });

        // Verifica se a resposta está OK (HTTP 200)
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }

        // Converte a resposta para JSON
        const result = await response.json();

        // Verifica o status retornado pelo valida.php
        if (result.status === 'success') { // Corrigido para 'success'
            alert(result.message);

            // Atualiza a página automaticamente após 500ms
            setTimeout(() => {
                location.reload();
            }, 500); // Adicionado o tempo de espera
        } else {
            alert('Erro: ' + result.message);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao processar o cadastro.');
    }
});
