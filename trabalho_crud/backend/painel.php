<?php
session_start();

header('Content-Type: application/json');

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "Usuário não autenticado"]);
    exit();
}

// Conectar com o banco de dados
include '../conexao.php';

// Primeiro nome do professor logado no painel
if (!isset($_SESSION['professor_primeiro_nome']) && isset($_SESSION['professor_nome'])) {
    $nomeCompleto = trim($_SESSION['professor_nome']);
    $_SESSION['professor_primeiro_nome'] = explode(' ', $nomeCompleto)[0];
}

echo json_encode([
    "professor_primeiro_nome" => $_SESSION['professor_primeiro_nome']
]);

$pdo = null;
?>
