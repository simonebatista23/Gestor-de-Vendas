<?php
include "menu.html";
require_once 'Classes/BaseModel.php';


$model = new BaseModel();



$produtos = $model->buscarProduto();
$clientes = $model->buscarClientes();


$data_inicial = isset($_GET['data_inicial']) ? $_GET['data_inicial'] : null;
$data_final = isset($_GET['data_final']) ? $_GET['data_final'] : null;
$cpf_cliente = isset($_GET['cpf_cliente']) ? $_GET['cpf_cliente'] : null;


$vendas = $model->buscarVendasComFiltro($data_inicial, $data_final, $cpf_cliente);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendas </title>
    <link rel="stylesheet" href="/src/css/style.css">
    <link rel="stylesheet" href="src/css/all.min.css">
    <script src="src/js/all.min.js"></script>
    <link rel="stylesheet" href="src/css/header.css">
    <link rel="stylesheet" href="src/css/navBar.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>


    <main>

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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
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

        <div class="container">

            <table class="product-table">
                <thead class="table-light" style="background-color: #f8f9fa; position:sticky; top:0; z-index:202;">
                    <tr>
                        <td colspan="4" data-select2-id="select2-data-6-9rg3" style="padding:5px;">
                            <form method="GET" action="">
                                <div style="display: flex; justify-content: flex-start; align-items: flex-end;">
                                    <label>
                                        Data Inicial
                                        <input type="date" name="data_inicial" style="width: auto; color: red;height:35px" class="form-control data-inicial" value="<?php echo isset($_GET['data_inicial']) ? $_GET['data_inicial'] : ''; ?>">
                                    </label>
                                    <label>
                                        Data Final
                                        <input type="date" name="data_final" style="width: auto; color: red; height:35px" class="form-control data-final" value="<?php echo isset($_GET['data_final']) ? $_GET['data_final'] : ''; ?>">
                                    </label>


                                    <br><br>
                                    <button type="submit" class="btn btn-primary btn-pesquisar" style="height:35px">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </form>


                        </td>
                    </tr>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">

                        </th>
                        <th scope="col"> Vendas</th>
                        <th scope="col" style="width: 70px;">Ação</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $contador = 1;
                    if (count($vendas) > 0) {
                        foreach ($vendas as $venda) {
                    ?>

                            <tr>

                                <td style="width: 50px; text-align: center;"><b class="idx"><?php echo "{$contador}"; ?> </b><br><br><code> <?php echo "{$venda['IdVenda']}"; ?></code></td>



                                <td style="text-align: center;width:50px;font-size: 28px;color: #20d937">
                                    <i class="fa-solid fa-print" style="color: #0d6efd"></i>
                                    <span style="text-align: center; width:50px; font-size: 28px; color: #20d937"><i
                                            class="fa-solid fa-clipboard-check"></i></span>
                                </td>
                                <td>
                                    <div class="accordion accordion-flush" id="accordionFlushExample-<?php echo "{$venda['IdVenda']}"; ?>  ">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingOne-<?php echo $venda['IdVenda']; ?>">
                                                <button style="background-color: #fff; position:static !important;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseOne-<?php echo $venda['IdVenda']; ?>" aria-expanded="false"
                                                    aria-controls="flush-collapseOne-<?php echo $venda['IdVenda']; ?>">
                                                    <div>
                                                        <?php echo $venda['NomeCliente']; ?> <br>
                                                        Data Pedido: <code><?php echo $venda['data_venda']; ?></code>
                                                        <p>Valor total: <strong> <?php

                                                                                    $itensVenda = $model->buscarCompraPorVenda($venda['IdVenda']);
                                                                                    $valorTotal = $itensVenda['valorTotal'];

                                                                                    echo "R$ " . number_format($valorTotal, 2, ',', '.');
                                                                                    ?></strong>
                                                        </p>


                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="flush-collapseOne-<?php echo $venda['IdVenda']; ?>" class="accordion-collapse collapse"
                                                aria-labelledby="flush-headingOne-<?php echo $venda['IdVenda']; ?>" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body" style=" background:#fff;text-align: center;">
                                                    <h4> CPF: <?php echo $venda['cpf_cliente']; ?></h4>
                                                    <h3>Valor total: <strong> <?php

                                                                                $itensVenda = $model->buscarCompraPorVenda($venda['IdVenda']);
                                                                                $valorTotal = $itensVenda['valorTotal'];

                                                                                echo "R$ " . number_format($valorTotal, 2, ',', '.');
                                                                                ?></strong>
                                                    </h3>


                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                <td style="text-align: end;   padding-right: 10px">
                                    <button
                                        data-id="<?php echo $venda['IdVenda']; ?>"
                                        class="btn btn-outline-primary btn-detalhes-venda"
                                        alt="Detalhes da Venda">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>


                                </td>


                            </tr>



                    <?php
                            $contador++;
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center;'>Nenhuma venda encontrada.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="offcanvas offcanvas-end " tabindex="-1" id="offcanvas" aria-modal="true" role="dialog" style="    width: 100%; ">
                <div class="offcanvas-header d-flex justify-content-between">
                    <div class="d-flex tela-titulo">
                        
                        <i class="fa-solid fa-box-open ico-produto"></i>
                        <h1 class="offcanvas-title nome-user" style="color: #000;">Produtos</h1>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <p>Detalhes da venda </p>
                    <div id="detalhesConteudo"></div>
                </div>
            </div>


        </div>

        </div>



    </main>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        *,
        ::after,
        ::before {
            box-sizing: border-box;
        }

        body {
            overflow: hidden;
        }


        main {
            height: 90vh;
            overflow-y: auto;
            overflow-x: hidden;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
            overflow-y: auto;
            margin-bottom: 30px;
        }

        .container {
            margin: 0;
            max-width: 100% !important;
            padding: 12px;
            margin-bottom: 30px
        }

        .btns #search-input {
            width: 75%;
            padding: 10px;
            font-size: 16px;
            height: 100%;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border: 1px solid #c1c1c1;
        }

        .btns {
            display: flex;
            height: 35px;
            align-items: center;
            width: 100%;

        }

        .btns #search-input:hover {
            border: 1px solid #2196f3;
        }


        .btn-buscar {
            background: none;
            border: 1px solid #c1c1c1;
            cursor: pointer;
            padding: 10px;
            height: 100%;
            border-radius: 5px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            width: 20%;
        }

        .product-table {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
            margin-top: 10px;
            margin-bottom: 30px;
            height: 100%;
        }

        .product-table th,
        .product-table td {
            text-align: left;
            border-bottom: 1px solid #ddd;
            height: 35px;
        }

        .product-table tbody tr:hover {
            background-color: #F0F0F0;
        }

        .product-table tr {
            height: 35px;
        }

        .product-table th {
            color: #333;
            font-weight: bold;
        }

        .product-table th.titulo {
            text-align: center;
            background-color: #F0F0F0;
        }

        .product-table tbody td:first-child {
            font-weight: bold;
            color: #d32f2f;
            width: 20%;
            text-align: center;
        }

        .product-table thead th.cod {
            text-align: center;
        }


        .cadastrar {
            color: #000;
        }

        .btns .btn-cadastrar {
            background: #5577d5;
            border: none;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.5s ease-in-out;
            color: #fff;
            text-align: center;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btns .btn-cadastrar:hover {
            background: #0d6efd;
        }

        .fechar:hover {
            background-color: #d9534f;
            color: white;
        }

        .btn-cadastro {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 5px;
        }



        .quantidade-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .btn-menos,
        .btn-mais {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            cursor: pointer;
            font-size: 16px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 5px;
            border-radius: 5px;
        }

        .btn-menos:hover,
        .btn-mais:hover {
            background-color: #e0e0e0;
        }



        .btn-outline-primary:hover {
            color: #fff;
        }

        .btn {
            border-radius:
                .25rem;
        }

        .idx {
            padding:
                2px;
            height: 20px;
            display: inline-block;
            border-radius:
                50%;
            color: black;
            line-height: 1;
            border:
                1px solid;
            border-left:
                4px solid;
        }

        .accordion-button::after {
            background-color: #fff;
        }

        .ico-produto {
            font-size: 54px;
            color: #673ab7;
            margin-right: 15px;
        }

        .select-cliente {
            height: 35px;
            border: 1px solid #ced4da;
            padding: 5px;
            font-size: 14px;
        }

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
        @media(max-width:400px){
            .data-inicial{
                width: 120px !important;
            }
            .data-final{
                width: 120px !important;
            }
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

        .select2-container .select2-selection--single {
            height: 35px !important;

        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: red !important;
            font-size: 1rem;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const buttons = document.querySelectorAll('.btn-detalhes-venda');
            const offcanvas = document.getElementById('offcanvas');
            const detalhesConteudo = document.getElementById('detalhesConteudo');

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const idVenda = button.getAttribute('data-id');


                    detalhesConteudo.innerHTML = '<p>Carregando detalhes...</p>';


                    fetch(`detalhes_compra.php?id_venda=${idVenda}`)
                        .then(response => response.text())
                        .then(data => {
                            detalhesConteudo.innerHTML = data;
                            const scripts = detalhesConteudo.querySelectorAll('script');
                            scripts.forEach(script => {
                                eval(script.textContent);

                            });
                        })
                        .catch(error => {
                            console.error('Erro ao carregar os detalhes:', error);
                            detalhesConteudo.innerHTML = '<p>Erro ao carregar os detalhes.</p>';
                        });

                    offcanvas.classList.add('show');
                    offcanvas.style.visibility = 'visible';
                });
            });


            const btnClose = offcanvas.querySelector('.btn-close');
            btnClose.addEventListener('click', function() {
                offcanvas.classList.remove('show');
                offcanvas.style.visibility = 'hidden';
            });
        });


        document.querySelectorAll('.btn-detalhes-venda').forEach(button => {
            button.addEventListener('click', function() {
                const idVenda = this.getAttribute('data-id');
                console.log('ID da Venda clicado:', idVenda);
            });
        });



        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(button => {
            button.addEventListener('click', function() {
                console.log('Accordion clicado: ', this.getAttribute('data-bs-target'));
            });
        });

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


        // função btn para mostrar a quantidade do produto selecionado
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


        //funcao formulario para produto selecionado

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
                document.body.classList.remove('modal-open');
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


    <?php include "rodape.html";
    ?>

</body>

</html>