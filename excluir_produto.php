<?php
require_once 'Classes/BaseModel.php';


$p = new BaseModel();

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $p->excluirProduto($id);
}
