<?php
include '../conexao.php';

$termo = isset($_GET['term']) ? $_GET['term'] : '';

$sql = "SELECT id, nome FROM aluno WHERE nome LIKE :termo";

$stmt = $pdo->prepare($sql);
$stmt->execute(['termo' => $termo . '%']);

$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$results = [];
foreach ($alunos as $row) {
    $results[] = [
        'id' => $row['id'],     // esse valor vai no form
        'text' => $row['nome']  // esse valor aparece pro usuário
    ];
}

header('Content-Type: application/json');
echo json_encode($results);
