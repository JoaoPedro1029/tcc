<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

// Conecta com o banco de dados
include '../conexao.php';

// Verifica se o ID do aluno foi passado
if (!isset($_GET['id'])) {
    echo "ID do aluno não fornecido.";
    exit();
}

$aluno_id = intval($_GET['id']); // segurança: garantir que é número inteiro

// Atualiza os dados se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $ano = $_POST['ano'];
    $sala = strtoupper($_POST['sala']);
    $email = $_POST['email'];

    // Concatenar o ano e a sala para formar a série
    if (in_array($ano, ['1', '2', '3'])) {
        $serie = $ano . 'º Ano EM ' . $sala;  // Ensino Médio
    } else {
        $serie = $ano . 'º Ano ' . $sala;
    }

    // Atualiza os dados no banco usando prepared statement
    $sql = "UPDATE aluno SET nome=?, serie=?, email=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nome, $serie, $email, $aluno_id])) {
        $_SESSION['mensagem_editar_aluno'] = "<p style='color: green;'>Dados atualizados com sucesso!</p>";
    } else {
        $errorInfo = $stmt->errorInfo();
        $_SESSION['mensagem_editar_aluno'] = "<p style='color: red;'>Erro ao atualizar: " . $errorInfo[2] . "</p>";
    }

    // Redireciona para a página de edição para mostrar a mensagem
    header("Location: ../frontend/editar_aluno_front.php?id=$aluno_id");
    exit();
}

// Busca os dados do aluno para exibir no formulário (GET)
$sql = "SELECT * FROM aluno WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$aluno_id]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    echo "Aluno não encontrado.";
    exit();
}

// Extrai o ano e a sala da série
preg_match('/(\d+)º Ano\s*(EM\s*)?(\w+)/', $aluno['serie'], $match);

// Ajusta os valores conforme a série
$ano = $match[1] ?? '';
$sala = $match[3] ?? '';

// Se o aluno está no EM, a variável $em será 'EM' (não está sendo usada diretamente no frontend, pode remover se quiser)
$em = isset($match[2]) ? 'EM' : '';
?>