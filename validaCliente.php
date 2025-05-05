<?php
require_once 'Classes/BaseModel.php';

$produto = new BaseModel();
header('Content-Type: application/json'); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $cpf = addslashes($_POST['cpf']) ?? null;
    $nome = addslashes($_POST['nome'] )?? null;

    if (empty($cpf) || empty($nome)) {
        echo json_encode(["status" => "error", "message" => "Todos os campos são obrigatórios."]);
        exit;
    }

    $dados = [
        'cpf' => $cpf,
        'nome' => $nome,
    ];

    try {
       
        if ($produto->inserir('cliente', $dados)) {
            echo json_encode(["status" => "success", "message" => "Cliente cadastrado com sucesso."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erro ao cadastrar o cliente."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Erro: " . $e->getMessage()]);
    }
    exit;
}
?>
