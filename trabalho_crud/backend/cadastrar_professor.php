<?php
session_start();
include '../conexao.php';

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Usar prepared statement para segurança
    $sql = "INSERT INTO professor (nome, cpf, email, senha) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nome, $cpf, $email, $senha_hash])) {
        $_SESSION['mensagem_professor'] = "<p style='color: green;'>Professor cadastrado com sucesso!</p>";
    } else {
        $errorInfo = $stmt->errorInfo();
        $_SESSION['mensagem_professor'] = "<p style='color: red;'>Erro ao cadastrar professor: " . $errorInfo[2] . "</p>";
    }

    $pdo = null;

    header("Location: ../frontend/cadastrar_professor_front.php");
    exit();
}
?>
