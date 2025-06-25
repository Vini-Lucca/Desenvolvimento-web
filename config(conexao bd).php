<?php
// Arquivo de configuração da conexão com o banco de dados

$host = 'localhost';     // Servidor do banco de dados
$db_name = 'academia_db';  // Nome do banco de dados
$username = 'root';      // Usuário do banco de dados
$password = '';          // Senha do banco de dados

try {
    // Criação do objeto PDO para a conexão
    $pdo = new PDO("mysql:host={$host};dbname={$db_name};charset=utf8", $username, $password);
    
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Em caso de falha na conexão, exibe o erro e encerra o script
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
