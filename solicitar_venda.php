<?php
require_once 'Classes/BaseModel.php';

$model = new BaseModel();
$produtos = $model->buscarProduto();
include "menu.html";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
</head>
<style>
    #sugestoes {
        max-height: 200px;
        overflow-y: auto;
        color: #000;
        position: absolute;
        z-index: 1000;
        width: 80%;
    }

    #sugestoes div {
        padding: 10px;
        cursor: pointer;
    }

    #sugestoes div:hover {
        background: #007bff;
    }

    .btn-selecionar {
        width: 100%;
        left: 0;
        position: absolute;
        background: transparent;
        border: none;
        top: 0;

    }

    .produto-item {
        position: relative;
        width: 100%;
        margin: 8px 0;
        font-size: 30px;
        padding: 8px;
        background-color: #f0f0f0;
    }

    .item {
        margin-bottom: 10px;
    }

    #lista-produtos {
        display: none;
        margin-top: 30px;
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #ccc;
        padding: 10px;
        background-color: #fff;
    }


    .produto-item:hover {
        background-color: #f0f0f0;
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
        width: 60px;
        background: transparent;
        border: none;
        outline: none;
    }

    .offcanvas {
        flex-grow: 1;
        padding:
            1rem 1rem;
        overflow-y: auto;
    }

    ::-webkit-scrollbar {
        width: 7px;
        height: 7px;
    }

    ::-webkit-scrollbar-track-piece {
        background-color: #f3f3f3;
        border-left:
            0px solid rgba(255, 204, 0, 1);
        border-right:
            5px solid #145293;
    }

    ::-webkit-scrollbar {
        width: 7px;
        height: 7px;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #ffc107;
        border-radius: 10px;
        border: 1px solid yellow;

    }


    ::-webkit-scrollbar-thumb:hover {
        background-color: gold;

    }

    #search-input {
        width: 80%;
        padding: 10px;
        font-size: 16px;
        height: 100%;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border: 1px solid #c1c1c1;
    }

    .btn-buscar {
        background-color: #145293;
        background: none;
        border: 1px solid #c1c1c1;
        cursor: pointer;
        height: 100%;
        border-radius: 5px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        width: 20%;
    }

    .btns-search {
        display: flex;
        height: 35px;
        align-items: center;
        width: 100%;
    }

    .cadastrar-venda {
        background-color: #5cb85c;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 5px;
    }

    .controle-quantidade {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-menos,
    .btn-mais {
        width: 30px;
        height: 30px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        cursor: pointer;
        border: 1px solid #c1c1c1;
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    .quantidade-input {
        width: 60px;
        text-align: center;
    }

    .valor {
        color: green;
        display: block;
        text-align: end;
    }

    .estoque {
        margin-left: 70px;
        color: #1089ad;
        display: block;
        text-align: end;
    }
</style>

<body>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="min-width: 70vw; ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar Venda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="cadastrar_venda.php" method="post">
                        <div class="mb-3">
                            <label for="data_venda" class="form-label" style="color: #000;">Data da Venda</label>
                            <input type="date" class="form-control" id="data_venda" name="data_venda" value="<?= date('Y-m-d'); ?>"> <!-- Data padrão será a data atual -->
                        </div>
                        <div class="btns-search">
                            <input type="text" placeholder="Digite o Código ou item para pesquisar" id="search-input" onkeyup="searchProduct()">
                            <button class="btn-buscar" onclick="searchProduct()"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
                        </div>
                        <input type="hidden" id="cpf_hidden" name="cpf_cliente" value="">

                        <div id="lista-produtos">
                            <?php foreach ($produtos as $p): ?>

                                <div class="produto-item" data-codigo="<?= $p['codigo_produto'] ?>" data-valor="<?= $p['valor'] ?>" data-qtde="<?= $p['qtde'] ?>">
                                    <span class="descricao"><?= $p['descricao'] ?></span>

                                    <span class="estoque">Estoque: <?= $p['qtde'] ?></span>
                                    <span class="valor">R$ <?= number_format($p['valor'], 2, ',', '.') ?></span>


                                    <input type="hidden" name="produtos[]" value="<?= $p['codigo_produto'] ?>" class="produto-codigo">

                                    <!-- Novo Controle de Quantidade -->
                                    <div class="controle-quantidade" style="display: none;">
                                        <button type="button" class="btn-menos">-</button>
                                        <input type="number" name="quantidades[]" class="quantidade-input" min="1" max="<?= $p['qtde'] ?>" value="1">
                                        <button type="button" class="btn-mais">+</button>
                                    </div>

                                    <button type="button" class="btn-selecionar" style="color: transparent;">s</button>
                                </div>


                            <?php endforeach; ?>

                            <button type="submit" class="cadastrar-venda">Cadastrar Venda</button>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="offcanvas offcanvas-start" tabindex="-1" id="demo" style="visibility: hidden; width: 600px;" aria-hidden="true">


        <div class="cadastrar" id="cad">
            <!-- Selecionar Cliente -->
            <div class="offcanvas-header d-flex justify-content-between">
                <div class="d-flex tela-titulo">
                    <i class="fa-solid fa-address-card ico-user-add" style="font-size: 54px; color:#1089ad; margin-right:15px"></i>
                    <h1 class="offcanvas-title nome-user" style="color:#000;">Clientes</h1>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="input-group mb-3">
                <input type="text" class="form-control input-descricao-cliente" id="cliente" name="cpf_cliente" autocomplete="off" placeholder="Digite o nome ou CPF" aria-label="Pesquise o cliente" aria-describedby="button-addon2">
                <button class="btn btn-outline-warning btn-buscar-cliente" type="button" id="button-addon2"><i class="fa-solid fa-magnifying-glass"></i></button>
                <button class="btn btn-outline-primary" id="reabrirModal"><i class="fa-solid fa-box"></i></button>

            </div>


            <div class="offcanvas-body">
                <div id="sugestoes"></div>

                <br><br>
            </div>

        </div>

    </div>

    <div class="container">
        <div class="flex-container" style="display: flex; gap: 10px;    margin-top: 20px;">
            <div><button class="btn btn-primary nova-solicitacao" data-bs-toggle="offcanvas" data-bs-target="#demo"><i class="fa-solid fa-address-card"></i> Nova Solicitação de Vendas </button></div>
            <div><a class="btn btn-outline-danger" target="_blank" href="">
                    <i class="fa-solid fa-file-contract"></i> Política Comercial</a></div>
            <div>
                <!-- <button data-cod="0" data-bs-toggle="modal" data-bs-target="#transmitir_solicitacao" class="btn btn-outline-primary transmitir-solicitacao"><i class="fa-solid fa-right-to-bracket"></i> Transmitir Solicitação</button> -->
            </div>
            <div></div>
        </div>

        <div class="modal fade" id="staticAlert" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel" style="color: red;"> Cliente</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modelBobyAlert" style="color: red;">
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" id="confirmar-cliente-excluir" class="btn btn-danger">Confirmar</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
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
                            const staticAlert = new bootstrap.Modal(document.getElementById('staticAlert'));
                            document.getElementById('modelBobyAlert').innerHTML = "<p>Erro ao buscar Sugestões</p>";
                            staticAlert.show();
                        }
                    });
                } else {
                    $("#sugestoes").hide();
                }
            });


            $(document).on("click", "#sugestoes div", function() {
                const cpf = $(this).data("cpf");
                $("#cliente").val(cpf);
                $("#cpf_hidden").val(cpf);
                $("#sugestoes").hide();
                $("#lista-produtos").fadeIn();


                var myModal = new bootstrap.Modal(document.getElementById('myModal'));
                myModal.show();

            });


        });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.produto-item').forEach(item => {
                const btnMais = item.querySelector('.btn-mais');
                const btnMenos = item.querySelector('.btn-menos');
                const quantidadeInput = item.querySelector('.quantidade-input');
                const maxQtde = parseInt(item.getAttribute('data-qtde'), 10);


                btnMais.replaceWith(btnMais.cloneNode(true));
                btnMenos.replaceWith(btnMenos.cloneNode(true));

                const novoBtnMais = item.querySelector('.btn-mais');
                const novoBtnMenos = item.querySelector('.btn-menos');


                novoBtnMais.addEventListener('click', () => {
                    let quantidade = parseInt(quantidadeInput.value, 10);
                    if (quantidade < maxQtde) {
                        quantidade++;
                        quantidadeInput.value = quantidade;
                    }
                });

                novoBtnMenos.addEventListener('click', () => {
                    let quantidade = parseInt(quantidadeInput.value, 10);
                    if (quantidade > 1) {
                        quantidade--;
                        quantidadeInput.value = quantidade;
                    }
                });
            });
        });

        document.querySelectorAll('.btn-selecionar').forEach(button => {
            button.addEventListener('click', function() {
                const produtoItem = button.closest('.produto-item');
                const controleQuantidade = produtoItem.querySelector('.controle-quantidade');

                if (!produtoItem.classList.contains('selecionado')) {
                    produtoItem.classList.add('selecionado');
                    controleQuantidade.style.display = 'flex';
                } else {
                    produtoItem.classList.remove('selecionado');
                    controleQuantidade.style.display = 'none';
                }
            });
        });


        document.querySelector('form').addEventListener('submit', function(event) {
            const produtosSelecionados = document.querySelectorAll('.produto-item.selecionado');
            const produtosValidos = [];


            produtosSelecionados.forEach(produto => {
                const quantidadeInput = produto.querySelector('.quantidade-input');
                const quantidade = quantidadeInput.value;

                if (quantidade > 0) {
                    produtosValidos.push({
                        produtoCodigo: produto.querySelector('.produto-codigo').value,
                        quantidade: quantidade
                    });
                }
            });


            if (produtosValidos.length === 0) {
                const staticAlert = new bootstrap.Modal(document.getElementById('staticAlert'));
                document.getElementById('modelBobyAlert').innerHTML = "<p>Por favor, selecione um produto.</p>";
                staticAlert.show();
                event.preventDefault();
            } else {

                const produtosInput = document.querySelectorAll('input[name="produtos[]"]');
                produtosInput.forEach(input => {
                    if (!produtosValidos.some(prod => prod.produtoCodigo === input.value)) {
                        input.disabled = true;
                    }
                });


                document.querySelectorAll('input[name="quantidades[]"]').forEach(input => {
                    input.remove();
                });


                produtosValidos.forEach((prod) => {
                    const quantidadeInput = document.createElement('input');
                    quantidadeInput.type = 'hidden';
                    quantidadeInput.name = 'quantidades[]';
                    quantidadeInput.value = prod.quantidade;
                    document.querySelector('form').appendChild(quantidadeInput);
                });
            }
        });


        $(document).ready(function() {
            const modalElement = document.getElementById('myModal');
            const modal = new bootstrap.Modal(modalElement);


            $('#reabrirModal').on('click', function() {
                const cpf = $("#cpf_hidden").val();

                if (cpf) {
                  
                    modalElement.classList.remove('fade');
                    modalElement.offsetWidth;
                    modalElement.classList.add('fade');

                    modal.show();
                } else {
                    const staticAlert = new bootstrap.Modal(document.getElementById('staticAlert'));
                    document.getElementById('modelBobyAlert').innerHTML = "<p>Por favor, selecione um cliente primeiro.</p>";
                    staticAlert.show();
                }
            });

           
            modalElement.addEventListener('hidden.bs.modal', function() {
                document.body.classList.remove('modal-open'); // Garante que o fundo será desbloqueado
                document.querySelectorAll('.modal-backdrop').forEach((backdrop) => backdrop.remove()); 

                document.querySelector('form').reset();
                $("#sugestoes").hide();
            });

        });

        function searchProduct() {
            const searchValue = document.getElementById('search-input').value.toLowerCase();
            const produtoItems = document.querySelectorAll('.produto-item');

            produtoItems.forEach(item => {
                const descricaoProduto = item.querySelector('.descricao').textContent.toLowerCase();
                const codigoProduto = item.getAttribute('data-codigo');


                if (codigoProduto.includes(searchValue) || descricaoProduto.includes(searchValue)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>

</body>

</html>