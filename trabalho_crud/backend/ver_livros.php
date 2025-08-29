<?php
session_start();
include 'conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

try {
    // Consulta para buscar todos os livros
    $sql = "SELECT id, nome_livro, nome_autor, isbn FROM livro ORDER BY nome_livro";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Armazenar os resultados na sessão para uso no frontend
    $_SESSION['livros'] = $livros;
    
    // Redirecionar de volta para a página de visualização
    header("Location: ../frontend/ver_livros_front.php");
    exit();
    
} catch (PDOException $e) {
    die("Erro ao buscar livros: " . $e->getMessage());
}
?>
