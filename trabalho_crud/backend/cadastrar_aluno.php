<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../conexao.php';

    try {
        $nome = $_POST['nome'];
        $ano = $_POST['ano'];
        $sala = $_POST['sala'];
        $email = $_POST['email'];

        if (in_array($ano, ['1', '2', '3'])) {
            $serie = $ano . 'º Ano EM ' . $sala;
        } else {
            $serie = $ano . 'º Ano ' . $sala;
        }

        $sql = "INSERT INTO aluno (nome, serie, email) VALUES (:nome, :serie, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':serie', $serie);
        $stmt->bindValue(':email', $email);

        if ($stmt->execute()) {
            $_SESSION['mensagem_aluno'] = "<p style='color: green;'>Aluno cadastrado com sucesso!</p>";
        } else {
            $_SESSION['mensagem_aluno'] = "<p style='color: red;'>Erro ao cadastrar aluno.</p>";
        }
    } catch (PDOException $e) {
        $_SESSION['mensagem_aluno'] = "<p style='color: red;'>Erro ao cadastrar aluno: " . $e->getMessage() . "</p>";
    }

    header("Location: ../frontend/cadastrar_aluno_front.php");
    exit();
}
?>

