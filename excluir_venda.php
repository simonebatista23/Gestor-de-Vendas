<?php
require_once 'Classes/BaseModel.php';

$model = new BaseModel();

if (isset($_GET['id_venda'])) {
    $idVenda = intval($_GET['id_venda']);

     $resultado = $model->excluir('venda', ['id_venda' => $idVenda]);

    if ($resultado) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'venda excluído com sucesso.']);
        header("vendas.php");
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'TESTE.']);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID da venda não fornecido.']);
}
