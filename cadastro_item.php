<?php
require_once 'Classes/BaseModel.php';


$model = new BaseModel();
$produtos = $model->buscarProduto();
include "menu.html";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Venda</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #sugestoes {
            max-height: 200px;
            overflow-y: auto;
            background: white;
            position: absolute;
            z-index: 1000;
            width: 100%;
        }

        #sugestoes div {
            padding: 10px;
            cursor: pointer;
        }

        #sugestoes div:hover {
            background: #f0f0f0;
        }

        .item {
            margin-bottom: 10px;
        }

        #lista-produtos {
            display: none;
        }

        .produto-item:hover {
            background-color: #f0f0f0;
        }

        .produto-item.selecionado {
            background-color: #cce5ff;
            border-color: #007bff;
        }

        .produto-item .descricao {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .produto-item .valor,
        .produto-item .estoque {
            font-size: 14px;
        }

        .quantidade-input {
            margin-top: 10px;
            width: 80px;
            display: none;
        }
    </style>
</head>

<body>
    <h1>Cadastro de Venda</h1>
    <form action="cadastrar_venda.php" method="post">
        <label for="cliente">Cliente (CPF ou Nome):</label>
        <input type="text" id="cliente" name="cpf_cliente" autocomplete="off" placeholder="Digite o nome ou CPF" required>
        <div id="sugestoes"></div>
        <br><br>

        <div id="itens">
            <div class="item">
                <label>Produto:</label>
                <select name="produtos[]" class="produto" required onchange="atualizarValor(this)">
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?= $p['codigo_produto'] ?>" data-valor="<?= $p['valor'] ?>" data-qtde="<?= $p['qtde'] ?>">
                            <?= $p['descricao'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Quantidade:</label>
                <input type="number" name="quantidades[]" class="quantidade" min="1" required onchange="verificarQuantidade(this)">
                <label>Valor Unitário:</label>
                <input type="text" name="valores[]" class="valor" readonly><br><br>
            </div>
        </div>
        <button type="button" onclick="adicionarItem()">Adicionar Item</button><br><br>
        <button type="submit">Cadastrar Venda</button>
    </form>

    <script>
        $(document).ready(function() {
           
            $("#cliente").on("input", function() {
                const query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: "cadastrar_venda.php",
                        method: "GET",
                        data: {
                            termo: query
                        },
                        success: function(data) {
                            $("#sugestoes").html(data).show();
                        },
                        error: function() {
                            alert("Erro ao buscar sugestões.");
                        }
                    });
                } else {
                    $("#sugestoes").hide();
                }
            });

         
            $(document).on("click", "#sugestoes div", function() {
                const cpf = $(this).data("cpf");
                $("#cliente").val(cpf);
                $("#sugestoes").hide();
                $("#lista-produtos").fadeIn();
            });

         
        });

        function atualizarValor(select) {
            const valor = select.options[select.selectedIndex].getAttribute('data-valor');
            const valorInput = select.parentElement.querySelector('.valor');
            valorInput.value = valor || '';
        }

        // Verifica a quantidade disponível no estoque
        function verificarQuantidade(input) {
            const select = input.parentElement.querySelector('.produto');
            const selectedOption = select.options[select.selectedIndex];

            if (!selectedOption || !selectedOption.hasAttribute('data-qtde')) {
                alert('Selecione um produto primeiro.');
                input.value = '';
                return;
            }

            const qtdeDisponivel = parseInt(selectedOption.getAttribute('data-qtde'), 10);
            const quantidadeSolicitada = parseInt(input.value, 10);

            if (quantidadeSolicitada > qtdeDisponivel) {
                alert('Quantidade solicitada maior do que o disponível em estoque.');
                input.value = '';
            }
        }

        function adicionarItem() {
            const div = document.createElement('div');
            div.classList.add('item');
            div.innerHTML = `
                <label>Produto:</label>
                <select name="produtos[]" class="produto" required onchange="atualizarValor(this)">
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?= $p['codigo_produto'] ?>" data-valor="<?= $p['valor'] ?>" data-qtde="<?= $p['qtde'] ?>">
                            <?= $p['descricao'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Quantidade:</label>
                <input type="number" name="quantidades[]" class="quantidade" min="1" required onchange="verificarQuantidade(this)">
                <label>Valor Unitário:</label>
                <input type="text" name="valores[]" class="valor" readonly><br><br>
            `;
            document.getElementById('itens').appendChild(div);
        }
    </script>
</body>

</html>