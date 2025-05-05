<?php
require_once 'Classes/BaseModel.php';

header('Content-Type: application/json');

$model = new BaseModel();

if (isset($_GET['id_item'])) {
    $idItem = $_GET['id_item'];

    $tabela = "item_venda";
    $condicao = ["id_itemVenda" => $idItem];

    if ($model->excluir($tabela, $condicao)) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Item excluído com sucesso.']);
        header("vendas.php");
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'TESTE.']);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID do item não fornecido.']);
}
