<?php
session_start();
include '../conexao.php';

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

// Verifica se o ID do professor foi passado
if (!isset($_GET['id'])) {
    $_SESSION['mensagem_editar_professor'] = "<p style='color: red;'>ID do professor não especificado.</p>";
    header("Location: ../frontend/ver_professores_front.php");
    exit();
}

$id = intval($_GET['id']);

// Atualização (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); // Remove pontos e traços
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (!empty($senha)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE professor SET nome = ?, cpf = ?, email = ?, senha = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$nome, $cpf, $email, $senha_hash, $id])) {
            $_SESSION['mensagem_editar_professor'] = "<p style='color: green;'>Dados atualizados com sucesso!</p>";
        } else {
            $errorInfo = $stmt->errorInfo();
            $_SESSION['mensagem_editar_professor'] = "<p style='color: red;'>Erro ao atualizar: " . $errorInfo[2] . "</p>";
        }
    } else {
        $sql = "UPDATE professor SET nome = ?, cpf = ?, email = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$nome, $cpf, $email, $id])) {
            $_SESSION['mensagem_editar_professor'] = "<p style='color: green;'>Dados atualizados com sucesso!</p>";
        } else {
            $errorInfo = $stmt->errorInfo();
            $_SESSION['mensagem_editar_professor'] = "<p style='color: red;'>Erro ao atualizar: " . $errorInfo[2] . "</p>";
        }
    }

    header("Location: ../frontend/editar_prof_front.php?id=$id");
    exit();
}

// Recupera dados do professor
$sql = "SELECT * FROM professor WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$prof = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prof) {
    $_SESSION['mensagem_editar_professor'] = "<p style='color: red;'>Professor não encontrado.</p>";
    header("Location: ../frontend/ver_professores_front.php");
    exit();
}

$prof_id = $prof['id'];
?>
