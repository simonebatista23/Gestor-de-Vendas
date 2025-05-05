<?php
require_once 'Classes/BaseModel.php';

$model = new BaseModel();

if (isset($_GET['id_venda'])) {
    $idVenda = $_GET['id_venda'];
    $dadosCompra = $model->buscarCompraPorVenda($idVenda);
    $itensCompra = $dadosCompra['itens'];
    $valorTotal = $dadosCompra['valorTotal'];
    $nomeCliente = $dadosCompra['nomeCliente'];
} else {
    echo "Venda não encontrada!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Detalhes da Venda</title>
    <link rel="stylesheet" href="src/css/NavBar.css">
    <link rel="stylesheet" href="src/css/all.min.css">
    <script src="src/js/all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        a {
            text-decoration: none;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-menu {
            height: 25px;
            width: 25px;

        }

        .table>:not(:first-child) {
            border-top: 2px solid currentColor;
        }

        .modal-backdrop {
            position: relative !important;
            top: 0;
            left: 0;
        }

        .btn-detalhes {
            background-color: #f8d7da;
            color: #d9534f;
            border: 1px solid #d9534f;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            transition: 0.3s all ease-in-out;
        }

        .btn-detalhes:hover {
            background-color: #d9534f;
            color: white;
        }



        .btn-venda-excluir {
            background-color: #f8d7da;
            color: #d9534f;
            border: 1px solid #d9534f;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .btn-venda-excluir:hover {
            background-color: #d9534f;
            color: white;
        }

        .teste {
            display: none;
            position: fixed;
            top: 0px;
            left: 0px;
            z-index: 1050;
            width: 100vw;
            height: 100vh;
            background-color: rgb(0, 0, 0);
            opacity: 0.5;

        }
    </style>
</head>

<body>


    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel" style="color: red;">Excluir item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick=" esconderFundo() "></button>
                </div>
                <div class="modal-body" id="modelBoby" style="color: red;">
                    <p>Deseja Realmente excluir o item?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick=" esconderFundo() ">Fechar</button>
                    <button type="button" id="confirmar-cancelamento" class="btn btn-danger">Confirmar</button>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="staticBackd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel" style="color: red;">Excluir Venda</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick=" esconderFundo() "></button>
                </div>
                <div class="modal-body" id="modelBobyVenda" style="color: red;">
                    <p>Deseja Realmente excluir a venda?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick=" esconderFundo() ">Fechar</button>
                    <button type="button" id="confirmar-venda-excluir" class="btn btn-danger">Confirmar</button>

                </div>
            </div>
        </div>
    </div>
    <div class="teste" id="fundo">

    </div>

    <h2 style="color: #000;">Itens da Venda Cliente: <?php echo $nomeCliente; ?></h2>
    <table class="table table-hover table-body-produtos" style="color: #000;">
        <thead class="table-light">
            <tr>
                <th style="text-align:center" scope="col">ID Venda</th>
                <th style="text-align:center" scope="col">Data da Venda</th>
                <th style="min-width: 200px;" scope="col">Produto</th>
                <th style="text-align:end" scope="col">Quantidade</th>
                <th style="text-align:end" scope="col">Valor Unitário (R$)</th>
                <th style="text-align:end" scope="col">Valor Total do Item (R$)</th>
                <th style="text-align:center" scope="col">Ações</th>
            </tr>
        </thead>
        <?php if (!empty($itensCompra)) : ?>
            <?php foreach ($itensCompra as $item) : ?>
                <tbody class="body-produtos">
                    <tr>
                        <td style="text-align:center"><?php echo htmlspecialchars($item['IdVenda']); ?></td>
                        <td style="text-align:center"><?php echo htmlspecialchars($item['DataVenda']); ?></td>
                        <td style="text-align:center"><?php echo htmlspecialchars($item['Produto']); ?></td>
                        <td style="text-align:right"><?php echo htmlspecialchars($item['Quantidade']); ?></td>
                        <td style="text-align:right"><?php echo number_format($item['ValorUnitario'], 2, ',', '.'); ?></td>
                        <td style="text-align:right"><?php echo number_format($item['ValorTotalItem'], 2, ',', '.'); ?></td>
                        <td style="text-align:center">
                            <!-- Formulário de Edição -->
                            <form action="editar_item.php" method="POST" id="form-<?php echo $item['IdItem']; ?>">
                                <input type="hidden" name="id_item" value="<?php echo $item['IdItem']; ?>">
                                <input type="hidden" name="id_venda" value="<?php echo $idVenda; ?>">

                                <div style="display: flex; align-items: center; justify-content: flex-end; height: 20px;
   ">
                                    <input type="checkbox" id="menu-hamburguer-<?php echo $item['IdItem']; ?>" class="menu-checkbox">
                                    <label for="menu-hamburguer-<?php echo $item['IdItem']; ?>" class="btn-menu"><i class="fa-regular fa-pen-to-square"></i></label>

                                    <nav class="menu-produto">
                                        <div class="container-teste">
                                            <div class="header">
                                                <h4>Editar/Excluir</h4>
                                                <label for="menu-hamburguer-<?php echo $item['IdItem']; ?>" class="btn-fechar">Fechar</label>
                                            </div>
                                            <div class="form">
                                                <div class="form-row first">
                                                    <label>Produto</label>
                                                    <input type="text" name="produto" value="<?php echo htmlspecialchars($item['Produto']); ?>" readonly>
                                                </div>
                                                <div class="form-row">
                                                    <label>Quantidade</label>
                                                    <input type="number" name="quantidade" value="<?php echo htmlspecialchars($item['Quantidade']); ?>" required>
                                                </div>
                                            </div>

                                            <div class="buttons">
                                                <button type="submit" class="btn-salvar">Salvar</button>

                                                <button type="button" class="btn-detalhes" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="<?php echo $item['IdItem']; ?>">
                                                    Excluir
                                                </button>

                                            </div>
                                        </div>
                                    </nav>
                                </div>
                            </form>
                        </td>
                    </tr>
                </tbody>
            <?php endforeach; ?>
            <tfoot style="font-size: 18px;">
                <tr>
                    <th>Total:</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align:end;">
                        <h3><?php echo number_format($valorTotal, 2, ',', '.'); ?></h3>
                    </th>
                    <th></th>
                </tr>
            </tfoot>
        <?php else : ?>
            <tr>
                <td colspan="7">Nenhum item encontrado para esta venda.</td>
            </tr>
        <?php endif; ?>
    </table>
    <div style="margin-top: 30px; ">

        <button type="button" data-id="<?php echo $idVenda; ?>" data-bs-toggle="modal" data-bs-target="#staticBackd" class="btn-venda-excluir">Excluir Venda</button>

    </div>


    <script>
        function esconderFundo() {
            var fundo = document.getElementById('fundo');
            fundo.style.display = "none";
        }

        function mostrarFundo() {
            var fundo = document.getElementById('fundo');
            fundo.style.display = "block";
        }


        const modal = document.getElementById('staticBackdrop');
        modal.addEventListener('hidden.bs.modal', function() {
            esconderFundo();
        });
        
        const modalVenda = document.getElementById('staticBackd');
        modalVenda.addEventListener('hidden.bs.modal', function() {
            esconderFundo();
        });


        let idItemSelecionado;

        document.querySelectorAll('.btn-detalhes').forEach(button => {
            button.addEventListener('click', function() {
                idItemSelecionado = this.getAttribute('data-id');
                mostrarFundo();

            });
        });


        const modelBoby = document.getElementById('modelBoby');

        document.getElementById('confirmar-cancelamento').addEventListener('click', function() {


            if (idItemSelecionado) {

                fetch(`excluir_itens.php?id_item=${idItemSelecionado}`, {
                        method: 'GET'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Resposta recebida:', data);
                        if (data.sucesso) {
                            modelBoby.innerHTML = "<p>Item excluído</p>";
                            const modal = bootstrap.Modal.getInstance(document.getElementById('staticBackdrop'));
                            modal.hide();
                            location.reload();
                        } else {
                            modelBoby.innerHTML = `<p>Erro ao excluir</p> ${data.mensagem}`;
                        }
                    })
                    .catch(error => {
                        console.error('Erro na requisição:', error);
                        modelBoby.innerHTML = "<p>Erro processar para excluir item</p>";
                    });

            }
        });


        // EXCLUIR VENDA
        let vendaSelecionada;
        document.querySelectorAll('.btn-venda-excluir').forEach(btn => {
            btn.addEventListener('click', function() {
                vendaSelecionada = this.getAttribute('data-id');
                mostrarFundo();
            });
        });


        const modelBobyVenda = document.getElementById('modelBobyVenda');

        document.getElementById('confirmar-venda-excluir').addEventListener('click', function() {

            if (vendaSelecionada) {

                fetch(`excluir_venda.php?id_venda=${vendaSelecionada}`, {
                        method: 'GET'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Resposta recebida:', data);
                        if (data.sucesso) {
                            modelBobyVenda.innerHTML = "<p>Venda excluído</p>";
                            const modal = bootstrap.Modal.getInstance(document.getElementById('staticBackd'));
                            modal.hide();
                            location.reload();
                        } else {
                            modelBobyVenda.innerHTML = `<p>Erro ao excluir</p> ${data.mensagem}`;
                        }
                    })
                    .catch(error => {
                        console.error('Erro na requisição:', error);
                        modelBobyVenda.innerHTML = "<p>Erro processar para excluir a Venda</p>";
                    });

            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const forms = document.querySelectorAll("form[id^='form-']");

            forms.forEach((form) => {
                form.addEventListener("submit", function(event) {
                    event.preventDefault();

                    const formData = new FormData(this);

                    fetch(this.action, {
                            method: "POST",
                            body: formData,
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.status === "success") {

                                location.reload();
                            } else {
                                console.log("erro")

                            }
                        })
                        .catch((error) => {
                            console.error("Erro:", error);
                            alert("Ocorreu um erro ao atualizar o item.");
                        });
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>

</html>