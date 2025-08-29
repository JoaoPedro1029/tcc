<?php
session_start();
include '../conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['professor_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit();
}

if (!isset($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID não enviado']);
    exit();
}

$id = intval($_POST['id']);
$sql = "DELETE FROM anotacoes WHERE id = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$id])) {
    echo json_encode(['success' => true, 'message' => 'Anotação excluída com sucesso']);
} else {
    http_response_code(500);
    $errorInfo = $stmt->errorInfo();
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $errorInfo[2]]);
}

$pdo = null;
?>
