<?php
// Inicia a sessão
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php"); // Redireciona para o login se não estiver logado
    exit();
}

// Conectar com o banco de dados
include '../conexao.php';

try {
    // Consulta para buscar todos os professores
    $sql = "SELECT * FROM professor";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $professores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Armazenar os resultados na sessão para uso no frontend
    $_SESSION['professores'] = $professores;
    
} catch (PDOException $e) {
    die("Erro ao buscar professores: " . $e->getMessage());
}

// Verifica se foi solicitado remover um professor
if (isset($_GET['remover'])) {
    $professor_id = $_GET['remover'];

    try {
        // Verifica se o professor está em algum empréstimo
        $check_emprestimo = "SELECT COUNT(*) as total FROM emprestimo WHERE id_professor = :professor_id";
        $stmt_check = $pdo->prepare($check_emprestimo);
        $stmt_check->bindParam(':professor_id', $professor_id, PDO::PARAM_INT);
        $stmt_check->execute();
        $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($result_check['total'] > 0) {
            echo "<script>alert('O professor está vinculado a um empréstimo. Primeiro remova o empréstimo para depois excluir o professor.');</script>";
        } else {
            // Remove o professor
            $sql_remover = "DELETE FROM professor WHERE id = :professor_id";
            $stmt_remover = $pdo->prepare($sql_remover);
            $stmt_remover->bindParam(':professor_id', $professor_id, PDO::PARAM_INT);
            $stmt_remover->execute();

            // Redireciona silenciosamente após exclusão
            header("Location: ../frontend/ver_professores_front.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Erro ao processar remoção: " . $e->getMessage());
    }
}
?>
