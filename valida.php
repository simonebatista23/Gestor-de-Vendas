<?php
require_once 'Classes/BaseModel.php';

$produto = new BaseModel();
header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
   addslashes( $codigo_produto = $_POST['codigo_produto']) ?? null;
 addslashes(   $descricao = $_POST['descricao']) ?? null;
    addslashes($qtde = $_POST['qtde']) ?? null;
addslashes(    $valor = $_POST['valor'] )?? null;

  
    if (empty($codigo_produto) || empty($descricao) || empty($qtde) || empty($valor)) {
        echo json_encode(["status" => "error", "message" => "Todos os campos são obrigatórios."]);
        exit;
    }

    $dados = [
        'codigo_produto' => $codigo_produto,
        'descricao' => $descricao,
        'qtde' => $qtde,
        'valor' => $valor
    ];

    try {
     
        if ($produto->inserir('produtos', $dados)) {
            echo json_encode(["status" => "success", "message" => "Produto cadastrado com sucesso."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erro ao cadastrar o produto."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Erro: " . $e->getMessage()]);
    }
    exit;
}
?>
<?php 



?>