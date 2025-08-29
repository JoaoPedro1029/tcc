<?php
include '../conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Buscar a data de devolução prevista
    $sql = "SELECT data_devolucao FROM emprestimo WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $emprestimo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($emprestimo) {
        $dataDevolucao = $emprestimo['data_devolucao'];
        $hoje = date('Y-m-d');

        if (!empty($dataDevolucao) && $dataDevolucao < $hoje) {
            $novoStatus = 1; // Atrasado
        } else {
            $novoStatus = 0; // Em andamento
        }

        $update = "UPDATE emprestimo SET status = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($update);
        
        if ($stmt_update->execute([$novoStatus, $id])) {
            echo "ok";
        } else {
            $errorInfo = $stmt_update->errorInfo();
            echo "Erro ao atualizar: " . $errorInfo[2];
        }
    } else {
        echo "Empréstimo não encontrado.";
    }
}
?>
