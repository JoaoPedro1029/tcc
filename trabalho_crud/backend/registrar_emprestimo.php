<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

include '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_aluno = $_POST['id_aluno'];
    $id_professor = $_SESSION['professor_id'];
    $id_livro = $_POST['id_livro'];

    // Converte datas do formato d/m/Y para Y-m-d
    $data_emprestimo = DateTime::createFromFormat('d/m/Y', $_POST['data_emprestimo']);
    $data_devolucao = DateTime::createFromFormat('d/m/Y', $_POST['data_devolucao']);

    if (!$data_emprestimo || !$data_devolucao) {
        $_SESSION['mensagem_emprestimo'] = "<p style='color: red;'>Formato de data inválido.</p>";
        header("Location: ../frontend/registrar_emprestimo_front.php");
        exit();
    }

    // Formata as datas para o padrão do banco
    $data_emprestimo = $data_emprestimo->format('Y-m-d');
    $data_devolucao = $data_devolucao->format('Y-m-d');

    if ($data_devolucao < $data_emprestimo) {
        $_SESSION['mensagem_emprestimo'] = "<p style='color: red;'>A data de devolução não pode ser anterior à data de empréstimo.</p>";
        header("Location: ../frontend/registrar_emprestimo_front.php");
        exit();
    }

    $status = 0; // Definindo o status do empréstimo como em andamento

    $sql = "INSERT INTO emprestimo (id_aluno, id_professor, id_livro, data_emprestimo, data_devolucao, status)
        VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$id_aluno, $id_professor, $id_livro, $data_emprestimo, $data_devolucao, $status])) {
        $_SESSION['mensagem_emprestimo'] = "<p style='color: green;'>Empréstimo registrado com sucesso!</p>";
    } else {
        $errorInfo = $stmt->errorInfo();
        $_SESSION['mensagem_emprestimo'] = "<p style='color: red;'>Erro ao registrar o empréstimo: " . $errorInfo[2] . "</p>";
    }

    header("Location: ../frontend/registrar_emprestimo_front.php");
    exit();
}

$pdo = null;
?>
