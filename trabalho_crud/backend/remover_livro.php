<?php
session_start();
include 'conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

// Verificar se o ID do livro foi fornecido
if (!isset($_GET['remover'])) {
    die("ID do livro não especificado.");
}

$id = $_GET['remover'];

try {
    // Verificar se o livro está sendo usado em algum empréstimo
    $sql_check = "SELECT COUNT(*) as total FROM emprestimo WHERE id_livro = :id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_check->execute();
    $result = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if ($result['total'] > 0) {
        $_SESSION['mensagem'] = "Não é possível remover este livro pois está sendo usado em empréstimos.";
        header("Location: ../frontend/ver_livros_front.php");
        exit();
    }
    
    // Remover o livro
    $sql = "DELETE FROM livro WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Livro removido com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao remover o livro.";
    }
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro ao remover livro: " . $e->getMessage();
}

// Redirecionar de volta para a lista de livros
header("Location: ../frontend/ver_livros_front.php");
exit();
?>
