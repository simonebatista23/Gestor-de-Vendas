<?php
require_once 'Classes/BaseModel.php';

$produto = new BaseModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    addslashes($codigo_produto = $_POST['codigo_produto']) ?? null;
    addslashes($descricao = $_POST['descricao']) ?? null;
    addslashes($qtde = $_POST['qtde']) ?? null;
    addslashes($valor = $_POST['valor']) ?? null;

    $dados = [
        'descricao' => $descricao,
        'qtde' => $qtde,
        'valor' => $valor
    ];

    $condicao = [
        'codigo_produto' => $codigo_produto
    ];

    if ($produto->atualizar('produtos', $dados, $condicao)) {

        echo json_encode(["status" => "success", "message" => "Produto atualizado com sucesso."]);

        header("location:index.php");
    } else {

        echo json_encode(["status" => "error", "message" => "Erro ao atualizar o produto."]);
    }
}
