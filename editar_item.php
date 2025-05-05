<?php
require_once 'Classes/BaseModel.php';

$model = new BaseModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idItem = $_POST['id_item'] ?? null;
    $quantidade = $_POST['quantidade'] ?? null;
    $item = $model->buscarEspecifico('item_venda', ['id_itemVenda' => $idItem]);

    if ($idItem && $quantidade) {
      
        $atualizado = $model->atualizar('item_venda', ['qtde' => $quantidade], ['id_itemVenda' => $idItem]);

        if ($atualizado) {
            echo json_encode(['status' => 'success', 'message' => 'Item atualizado com sucesso!']);

            header("Location: detalhes_compra.php?id_venda=" . $item['id_venda']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar o item.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Dados inválidos.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido.']);
}
