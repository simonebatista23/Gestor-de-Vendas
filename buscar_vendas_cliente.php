<?php
include "menu.html";
require_once 'Classes/BaseModel.php';

$model = new BaseModel();


$produtos = $model->buscarProduto();
$clientes = $model->buscarClientes();

$data_inicial = isset($_GET['data_inicial']) ? $_GET['data_inicial'] : null;
$data_final = isset($_GET['data_final']) ? $_GET['data_final'] : null;
$cpf_cliente = isset($_GET['cpf_cliente']) ? $_GET['cpf_cliente'] : null;


if ($cpf_cliente && $data_inicial && $data_final) {
    $vendas = $model->buscarVendasComFiltro($data_inicial, $data_final, $cpf_cliente);
}


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

        <div class="container">

            <table class="product-table">
                <thead class="table-light" style="background-color: #f8f9fa; position:sticky; top:0; z-index:202;">
                    <tr>
                        <td colspan="4" data-select2-id="select2-data-6-9rg3" style="padding:5px;">
                            <form method="GET" action="">
                                <div class="container-data" style="display: flex; justify-content: flex-start; align-items: flex-end;">
                                    <label>
                                        Data Inicial
                                        <input type="date" name="data_inicial" style="width: auto; color: red;height:35px" class="form-control data-inicial" value="<?php echo isset($_GET['data_inicial']) ? $_GET['data_inicial'] : ''; ?>">
                                    </label>
                                    <label>
                                        Data Final
                                        <input type="date" name="data_final" style="width: auto; color: red; height:35px" class="form-control data-final" value="<?php echo isset($_GET['data_final']) ? $_GET['data_final'] : ''; ?>">
                                    </label>
                                    <select class="select-cliente" id="select-cliente" name="cpf_cliente" style="width: 100%;" required>
                                        <option value="">Selecione um cliente</option>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <option value="<?= $cliente['cpf'] ?>">
                                                <?= $cliente['nome'] ?> [CPF: <?= $cliente['cpf'] ?>]
                                            </option>
                                        <?php endforeach; ?>
                                    </select>



                               
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
                    if ($cpf_cliente && $data_inicial && $data_final && count($vendas) > 0) {
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
                                                <button style="background-color: #fff;position:static !important" class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
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
                        <img style="width: 100%; display: none;" src="https://app.ultraparksoft.com.br/app/imagens/ULTRAPARK-PRETO.png">
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
            <div id="detalhesConteudo">

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

        #lista-produtos {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .produto-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 180px;
            position: relative;
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

        .quantidade-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
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

        @media(max-width:540px) {
            .container-data {
                flex-direction: column;
                align-items: self-start !important;
            }
            .container-data label{
                width: 100%;
            }
            .data-inicial{
                width: 100% !important;
            }
            .data-final{
                width: 100% !important;
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


            document.querySelectorAll('.quantidade-input').forEach(input => {
                input.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            });

            document.querySelectorAll('.quantidade-input').forEach(input => {
                input.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            });


            document.querySelectorAll('.produto-item').forEach(item => {
                item.addEventListener('click', function(event) {
                    const inputProduto = this.querySelector('input[name="produtos[]"]');
                    const quantidadeContainer = this.querySelector('.quantidade-container');
                    const inputQuantidade = this.querySelector('.quantidade-input');

                    if (event.target.classList.contains('quantidade-input')) {
                        return;
                    }

                    if (this.classList.contains('selecionado')) {

                        this.classList.remove('selecionado');
                        inputProduto.disabled = true;
                        quantidadeContainer.style.display = 'none';
                    } else {

                        this.classList.add('selecionado');
                        inputProduto.disabled = false;
                        quantidadeContainer.style.display = 'flex';
                    }
                });
            });

            function alterarQuantidade(button, delta) {
                const input = button.parentElement.querySelector('.quantidade-input');
                const maxQtde = parseInt(input.getAttribute('max'), 10);
                const novaQuantidade = parseInt(input.value) + delta;

                if (novaQuantidade > 0 && novaQuantidade <= maxQtde) {
                    input.value = novaQuantidade;
                } else if (novaQuantidade > maxQtde) {
                    alert('Quantidade solicitada maior do que o disponível em estoque.');
                    input.value = maxQtde;
                } else {
                    input.value = 1;
                }
            }

            // Atualiza o valor unitário com base no produto selecionado
            function atualizarValor(select) {
                const valor = select.options[select.selectedIndex].getAttribute('data-valor');
                const valorInput = select.parentElement.querySelector('.valor');
                valorInput.value = valor || '';
            }


            // Função para alterar quantidade usando botões "+" e "-"
            function alterarQuantidade(button, delta) {
                const input = button.parentElement.querySelector('.quantidade');
                const novaQuantidade = parseInt(input.value) + delta;
                input.value = novaQuantidade > 0 ? novaQuantidade : 1;
                verificarQuantidade(input);
            }


            function verificarQuantidade(input) {
                const maxQtde = parseInt(input.getAttribute('max'), 10);
                if (parseInt(input.value, 10) > maxQtde) {
                    alert('Quantidade solicitada maior do que o disponível em estoque.');
                    input.value = maxQtde;
                }
            }



            $(document).ready(function() {
                $('#select-cliente').select2({
                    placeholder: 'Selecione o Cliente',
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return "Nenhum cliente encontrado.";
                        }
                    }
                });


                $('#select-cliente').on('select2:open', function() {
                    setTimeout(function() {
                        $('.select2-search__field').attr('placeholder', 'Digite o nome ou CPF do cliente');
                    }, 10);
                });
            });

        });
    </script>

    <script src="src/js/vendas.js"></script>
    <?php include "rodape.html";
    ?>

</body>

</html>