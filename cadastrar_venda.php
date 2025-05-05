<?php
require_once 'Classes/BaseModel.php';


$model = new BaseModel();


if (isset($_GET['termo'])) {
    $termo = $_GET['termo'];

 
    $sql = "SELECT cpf, nome FROM cliente WHERE cpf LIKE :termo OR nome LIKE :termo LIMIT 10";
    $stmt = $model->getPDO()->prepare($sql);
    $stmt->bindValue(':termo', "%$termo%", PDO::PARAM_STR);
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($clientes as $cliente) {
        echo "<div data-cpf='{$cliente['cpf']}'>{$cliente['nome']} ({$cliente['cpf']})</div>";
    }
    exit; 
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cpf_cliente = $_POST['cpf_cliente'];
    $produtos = $_POST['produtos'] ?? [];
    $quantidades = $_POST['quantidades'] ?? [];
    $data_venda = $_POST['data_venda'] ?? date('Y-m-d'); 
    if (count($produtos) !== count($quantidades)) {
        echo "Erro: a quantidade de produtos e quantidades não corresponde!";
        exit;
    }

    try {
        
        $pdo = $model->getPDO();
        $pdo->beginTransaction();

      
        $id_venda = $model->cadastraVenda($cpf_cliente, $data_venda); 

        
        for ($i = 0; $i < count($produtos); $i++) {
            $codigo_produto = $produtos[$i];
            $qtde = $quantidades[$i];

        
            $produtoAtual = $model->buscarProdutoEspecifico($codigo_produto);

            if ((int)$produtoAtual['qtde'] < (int)$qtde) {
                throw new Exception('Estoque insuficiente para o produto: ' . $produtoAtual['descricao']);
            }

          
            $model->itemVenda($id_venda, $codigo_produto, $qtde, $produtoAtual['valor']);

           
        }

     
        $pdo->commit();
      header("location:vendas.php");
    } catch (Exception $e) {
        
        $pdo->rollBack();
        echo "Erro ao cadastrar a venda: " . $e->getMessage();
    }
}
?>
