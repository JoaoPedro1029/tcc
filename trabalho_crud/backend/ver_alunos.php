<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php"); // Redireciona para o login se não estiver logado
    exit();
}

// Conectar com o banco de dados
include '../conexao.php';

// Verifica se foi solicitado remover um aluno
if (isset($_GET['remover'])) {
    $aluno_id = $_GET['remover'];

    // Verifica se o aluno está em algum empréstimo
    $check_emprestimo = "SELECT * FROM emprestimo WHERE id_aluno = ?";
    $stmt_check = $pdo->prepare($check_emprestimo);
    $stmt_check->execute([$aluno_id]);
    $result_check = $stmt_check->fetchAll();

    if (count($result_check) > 0) {
        echo "<script>alert('O aluno está registrado em um empréstimo. Primeiro remova o empréstimo para depois excluir o aluno.');</script>";
    } else {
        // Remove o aluno
        $sql_remover = "DELETE FROM aluno WHERE id = ?";
        $stmt_remover = $pdo->prepare($sql_remover);
        $stmt_remover->execute([$aluno_id]);

        // Redireciona silenciosamente após exclusão
        header("Location: ../frontend/ver_alunos_front.php");
        exit();
    }
}

// Consulta para buscar todos os alunos
$sql = "SELECT * FROM aluno";
$stmt = $pdo->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fechar a conexão
$pdo = null;
?>