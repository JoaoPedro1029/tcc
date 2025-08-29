<?php

// Inclui o arquivo que contém a função para carregar o .env
require_once 'load_env.php';

// Carrega as variáveis de ambiente a partir do arquivo .env
loadEnv(__DIR__ . '/.env');

// Obtém as variáveis de ambiente para conexão com o banco de dados
$host = $_ENV['HOST'];       // Endereço do servidor de banco de dados
$usuario = $_ENV['USUARIO']; // Nome de usuário do banco
$senha = $_ENV['SENHA'];     // Senha do banco
$banco = $_ENV['BANCO'];     // Nome do banco de dados

try {
    // Tenta estabelecer a conexão com o banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $usuario, $senha);
    
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Conexão estabelecida com sucesso (não exibe mensagem para segurança e boas práticas)
} catch (PDOException $e) {
    // Em caso de erro, registra a mensagem detalhada no log do servidor
    error_log($e->getMessage());

    // Exibe uma mensagem genérica ao usuário, sem revelar detalhes sensíveis
    die('Erro ao conectar ao banco de dados. Tente novamente mais tarde.');
}

?>
