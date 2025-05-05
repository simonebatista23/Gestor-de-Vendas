<?php
require_once 'Classes/BaseModel.php';

$cliente = new BaseModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $cpf = addslashes($_POST['cpf']) ?? null;
    $nome = addslashes($_POST['nome'] )?? null;
   
    $dados = [
        'cpf' => $cpf,
        'nome' => $nome,
       
    ];

    $condicao = [
        'cpf' => $cpf
    ];

    if ($cliente->atualizar('cliente', $dados, $condicao)) {

        echo json_encode(["status" => "success", "message" => "Cliente atualizado com sucesso."]);
       
        header("location:clientes.php");
    } else {
      
        echo json_encode(["status" => "error", "message" => "Erro ao atualizar o cliente."]);
    }
}
?>
    