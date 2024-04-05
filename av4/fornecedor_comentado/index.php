<?php
// Inclui os arquivos de classe necessários
include_once 'conexao.php';
include_once 'fornecedor.php';

// Cria uma nova instância da classe de conexão
$conexao_banco = new Conexao();
// Conecta-se ao banco de dados
$banco = $conexao_banco->conectar();

// Cria uma nova instância da classe Fornecedor, passando o objeto de conexão como parâmetro
$fornecedor = new Fornecedor($banco);

// Verifica se a requisição foi feita com o método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos do formulário estão preenchidos
    if (!empty($_POST['fornecedor']) && !empty($_POST['celular'])) {
        // Atribui os valores dos campos do formulário às propriedades do objeto Fornecedor
        $fornecedor->fornecedor = $_POST['fornecedor'];
        $fornecedor->celular = $_POST['celular'];

        // Tenta criar um novo fornecedor no banco de dados
        if ($fornecedor->criar()) {
            echo "<p>Fornecedor criado com sucesso.</p>";
        } else {
            echo "<p>Não foi possível criar o fornecedor.</p>";
        }
    } else {
        echo "<p>Por favor, preencha todos os campos.</p>";
    }
}

// Executa a consulta para ler todos os fornecedores cadastrados
$stmt = $fornecedor->ler();
// Obtém o número de linhas retornadas pela consulta
$num = $stmt->rowCount();

// Verifica se há fornecedores cadastrados
if ($num > 0) {
    echo "<h2>Fornecedores Cadastrados</h2>";
    echo "<div class='lista-fornecedores'>";
    echo "<ul>";
    // Itera sobre os resultados da consulta e exibe cada fornecedor
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        echo "<li>ID: {$id}, Fornecedor: {$fornecedor}, Celular: {$celular}</li>";
    }
    echo "</ul>";
    echo "</div>";
} else {
    echo "<p>Nenhum fornecedor cadastrado.</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Fornecedores</title>
    <link rel="stylesheet" href="estilo.css"> <!-- Inclui o arquivo de estilo CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- Inclui a biblioteca jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script> <!-- Inclui a biblioteca jQuery Mask -->
</head>
<body>
    <div class="container">
        <h2>Adicionar Novo Fornecedor</h2>
        <!-- Formulário para adicionar um novo fornecedor -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="fornecedor">Fornecedor:</label>
            <input type="text" id="fornecedor" name="fornecedor" required> <!-- Campo de texto para o nome do fornecedor -->

            <label for="celular">Celular:</label>
            <input type="text" id="celular" name="celular" required> <!-- Campo de texto para o número de celular do fornecedor -->

            <input type="submit" value="Adicionar Fornecedor"> <!-- Botão de envio do formulário -->
        </form>
    </div>
    <script>
        $(document).ready(function(){
            $('#celular').mask('(00)0 0000-0000'); // Aplica a máscara de formatação para o campo de celular usando jQuery Mask
        });
    </script>
</body>
</html>
