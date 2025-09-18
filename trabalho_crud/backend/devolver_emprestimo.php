<?php
session_start();

if (!isset($_SESSION['professor_id'])) {
    http_response_code(403);
    echo "Acesso negado.";
    exit();
}

include '../conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "UPDATE emprestimo SET status = 2 WHERE id = ?";
    
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$id])) {
        echo "ok";
    } else {
        http_response_code(500);
        echo "Erro ao atualizar o status.";
    }
} else {
    http_response_code(400);
    echo "ID inválido.";
}

$pdo = null;
?>
