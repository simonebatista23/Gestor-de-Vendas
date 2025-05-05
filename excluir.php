<?php
require_once 'BaseModel.php';
$model = new BaseModel();

if (isset($_GET['codigo'])) {
    $model->excluir('produtos', ['codigo_produto' => $_GET['codigo']]);
    echo "Produto excluído com sucesso!";
}
?>
