<?php
require_once 'Classes/BaseModel.php';
$model = new BaseModel();
$produtos = $model->buscarTodos('produtos');
echo json_encode($produtos);
?>
