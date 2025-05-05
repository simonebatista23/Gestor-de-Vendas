<?php
require_once "Classes/BaseModel.php";

$p = new BaseModel();
include "menu.html";
 include "rodape.html";

 function formatarCPF($cpf) {
 
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    if (strlen($cpf) === 11) {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }

    return $cpf;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product </title>
    <link rel="stylesheet" href="src/css/style.css">
    <link rel="stylesheet" href="src/css/all.min.css">
    <script src="src/js/all.min.js"></script>
    <link rel="stylesheet" href="src/css/header.css">
    <link rel="stylesheet" href="src/css/navBar.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
    <script defer>

        document.addEventListener("DOMContentLoaded", function() {

            const btnSalvarList = document.querySelectorAll(".btn-salvar");
            btnSalvarList.forEach(btn => {
                btn.addEventListener("click", function() {
                    const containerTeste = this.closest(".container-teste");
                    const cpfCliente = containerTeste.querySelector("[name='cpf']").value;
                    const nome = containerTeste.querySelector("[name='nome']").value;



                    fetch('atualizarCliente.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                cpf_clinete: cpfCliente,
                                nome: nome,

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
                            console.error("Erro na atualizaÃ§Ã£o:", error);
                        });
                });
            });
        });

        function excluirProduto(id) {
            let modelProduto = document.getElementById("modelBobyExcluir");
            let modelExcluir = document.getElementById("confirmar-produto-excluir");


            modelExcluir.addEventListener('click', function() {

                fetch('excluirCliente.php', {
                        method: 'POST',
                        body: new URLSearchParams({
                            id: id
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            modelProduto.innerHTML = "<p> Produto excluindo com Sucesso </p>"
                            setTimeout(() => {
                                location.reload();
                            }, 700);
                        } else {
                            modelProduto.innerHTML = "<p> Erro ao excluir produto</p>"
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        modelProduto.innerHTML = "<p> Erro ao procssar para excluir produto</p>"
                    });
            });
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


    </script>
 <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            overflow: hidden;
        }

        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
            width: 100%;
            height: 100vh;
            overflow-y: auto;
        }

        .container {
            width: 75%;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 60px
        }

        .btns #search-input {
            width: 100%;
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
             padding:0;
             font-size: 10px;
            height: 100%;
            border-radius: 5px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
           
        }
  

        @media(max-width:545px) {
            .btn-buscar {
                width: 100%;

            }

        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .product-table th,
        .product-table td {
            text-align: left;
            border-bottom: 1px solid #ddd;
            height: 35px;
        }

        @media(max-width:500px) {
            .product-table td {
                /* text-align: center; */
                padding-left: 15px;
            }
        }

        @media(max-width:645px) {
            .btn-buscar {
                width: 20%;

           }

           .span-buscar {
                 display: none;
           }

          body{
              overflow-y: auto;
           }
      

        }
        .product-table tbody td:first-child {
            font-weight: bold;
            color: #d32f2f !important;
            width: 20%;
            text-align: center;
            text-decoration: none;
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
            padding-left: 10px;
        }

        .product-table thead th.cod {
            text-align: center;
        }

        .product-table thead th:nth-child(2) {
            width: 40%;
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
            width: 5%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btns .btn-cadastrar:hover {
            background: #0d6efd;
        }



        .cadastrar {
            position: fixed;
            top: 10%;
            right: 0;
            max-height: 80vh;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            overflow-y: auto;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease-in-out;
            z-index: 1000;
        }

        .container-cadastrar {
            max-width: 400px;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            z-index: 10;
        }

        .cadastrar.active {
            opacity: 1;
            pointer-events: all;
        }

        .fechar {
            background-color: #f8d7da;
            color: #d9534f;
            border: 1px solid #d9534f;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
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
    .modal.fade .modal-dialog {
     transition: transform .3s ease-out;
     transform: none !important;
    }
     .modal.fade{
      top: 100px !important;
    }

    </style>

</head>

<body>

    <main>

        <div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel" style="color: #333;">Cadastro Cliente</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modelBoby" style="color: red;">
                        <h3></h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="staticExcluir" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel" style="color: red;">Excluir cliente</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modelBobyExcluir" style="color: red;">
                        <p>Deseja Realmente excluir o cliente?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" id="confirmar-produto-excluir" class="btn btn-danger">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="btns">

                <button type="button" class="btn-cadastrar" onclick="toggleCadastroForm()"><i class="fa-solid fa-circle-plus"></i></button>
                <input type="text" placeholder="Digite o CÃ³digo ou item para pesquisar" id="search-input" onkeyup="searchProduct()">
                <button class="btn-buscar" onclick="searchProduct()"><i class="fa-solid fa-magnifying-glass"></i><span class="span-buscar">Buscar</span></button>

      

            </div>

          <form action="valida.php" method="post" id="form">
                    <div class="cadastrar" id="cad">
                        <div class="header">
                            <h2 style="font-size: 16px;">Cadastrar do Cliente</h2>
                            <label class="fechar" id="close" onclick="fecharCadastroForm()">Fechar</label>
                        </div>
                        <div class="container-cadastrar">
                            <div class="form">
                                <div class="form-row first">
                                    <label>CPF</label>
                                    <input type="text" name="cpf" readonly maxlength="11" minlength="11">
                                </div>
                                <div class="form-row">
                                    <label>Nome</label>
                                    <input type="text" name="nome" readonly minlength="3">
                                </div>


                            </div>
                            <div class="buttons">
                                <button type="submit" class="btn-cadastro" style="pointer-events: none;
                                opacity: 0.5;">Cadastrar</button>
                            </div>
                        </div>
                    </div>
                </form>


            <table class="product-table">
                <thead>
                    <tr>
                        <th colspan="6" class="titulo">Clientes</th>
                    </tr>
                    <tr>
                        <th class="cod" style="width: 85px; ">CPF Cliente</th>

                        <th class="nome-cli" style="width: 75%;  text-align: left;">Nome Cliente</th>

                        <th></th>
                    </tr>
                </thead>
                <tbody>

                <tbody>
                    <?php
                    $dados = $p->buscarTodos('cliente');

                    if (count($dados) > 0) {
                        foreach ($dados as $index => $produto) {
                            echo "<tr>";
                           echo "<td>" . formatarCPF($produto['cpf']) . "</td>";
                            echo "<td>{$produto['nome']}</td>";

                    ?>
                            <form action="atualizarCliente.php" method="POST" id="form">
                                <td style="  display: flex;
    align-items: center;
    justify-content: flex-end;">
                                    <input type="checkbox" id="menu-hamburguer-<?= $index ?>" class="menu-checkbox">
                                    <label for="menu-hamburguer-<?= $index ?>" class="btn-menu"><i class="fa-regular fa-pen-to-square"></i></label>


                                    <nav class="menu-produto">
                                        <div class="container-teste">
                                            <div class="header">
                                                <h2 style="font-size: 16px !important;">Detalhes do Cliente</h2>
                                                <label for="menu-hamburguer-<?= $index ?>" class="btn-fechar">Fechar</label>
                                            </div>
                                            <div class="form">
                                                <div class="form-row first">
                                                    <label>CPF</label>
                                                    <input type="text" name="cpf" value="<?= $produto['cpf'] ?>" readonly>
                                                </div>
                                                <div class="form-row">
                                                    <label>Nome</label>
                                                    <input type="text" name="nome" value="<?= $produto['nome'] ?>" readonly>
                                                </div>

                                            </div>

                                            <div class="buttons">
                                                <div class="checar">

                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked style="    pointer-events: none;
  							  opacity: 0.5;">
                                                        <label class="form-check-label" for="flexSwitchCheckChecked">Ativo</label>
                                                    </div>

                                                </div>

                                                <div class="buttos-up-del">
                                                    <button type="submit" class="btn-salvar" style="    pointer-events: none;
    							opacity: 0.5;">Salvar</button>
                                                    <button type="button" class="btn-excluir" style="pointer-events: none;
      							  opacity: 0.5;" data-bs-toggle="modal" data-bs-target="#staticExcluir">Excluir</button>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>
                                </td>
                            </form>


                    <?php
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>

                </tbody>
            </table>
        </div>
    </main>
   <script>
        const modelBoby = document.getElementById('modelBoby');
 console.log("teste");

        document.getElementById('form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            try {
                const response = await fetch('validaCliente.php', {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor');
                }

                const result = await response.json();

                const modalElement = document.getElementById('staticBackdrop');
                const modal = new bootstrap.Modal(modalElement);

                if (result.status === 'success') {
                    modelBoby.innerHTML = "<p>Cadastrado com sucesso</p>";
                    modal.show();


                    setTimeout(() => {
                        location.reload();
                    }, 700);
                } else {
                    modelBoby.innerHTML = "<p>Erro ao Cadastrar</p>";
                    modal.show();
                }
            } catch (error) {
                console.error('Erro:', error);
                modelBoby.innerHTML = "<p>Erro porcessar o Cadastro</p>";
            }
        });


</script>


    <!-- <script src="src/js/clientes.js"></script> -->
    <?php include "rodape.html";
    ?>



</body>

</html>