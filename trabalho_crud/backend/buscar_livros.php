<?php
include '../conexao.php';

$termo = isset($_GET['term']) ? $_GET['term'] : '';

$sql = "SELECT id, nome_livro FROM livro WHERE nome_livro LIKE :termo";

$stmt = $pdo->prepare($sql);
$stmt->execute(['termo' => $termo . '%']);

$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

$results = [];
foreach ($livros as $row) {
    $results[] = [
        'id' => $row['id'],
        'text' => $row['nome_livro']
    ];
}

header('Content-Type: application/json');
echo json_encode($results);
