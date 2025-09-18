<?php
session_start();
include 'conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

// Buscar dados do livro para edição
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $sql = "SELECT id, nome_livro, nome_autor, isbn FROM livro WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $livro = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$livro) {
            die("Livro não encontrado.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar livro: " . $e->getMessage());
    }
} else {
    die("ID do livro não especificado.");
}

// Processar formulário de edição
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $isbn = trim($_POST['isbn']);
    
    try {
        $sql = "UPDATE livro SET nome_livro = :titulo, nome_autor = :autor, isbn = :isbn WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':autor', $autor, PDO::PARAM_STR);
        $stmt->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Livro atualizado com sucesso!";
            header("Location: ../frontend/ver_livros_front.php");
            exit();
        } else {
            $erro = "Erro ao atualizar o livro.";
        }
    } catch (PDOException $e) {
        $erro = "Erro ao atualizar livro: " . $e->getMessage();
    }
}
?>
