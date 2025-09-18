# TODO - Substituição de $conn por $pdo

## Arquivos a serem modificados:
- [ ] backend/cadastrar_professor.php
- [ ] backend/cadastrar_livros.php
- [ ] backend/cancelar_devolucao.php
- [ ] backend/devolver_emprestimo.php
- [ ] backend/editar_aluno.php
- [ ] backend/editar_emprestimo.php
- [ ] backend/editar_prof.php
- [ ] backend/esqueci_senha.php
- [ ] backend/excluir_anotacao.php
- [ ] backend/login.php
- [ ] backend/painel.php
- [ ] backend/registrar_emprestimo.php
- [ ] backend/redefinir_senha.php
- [ ] backend/relatorios.php
- [ ] backend/salvar_anotacao.php
- [ ] backend/ver_alunos.php
- [ ] frontend/relatorios_front.php

## Padrões de substituição:
1. Remover verificações `if ($conn->connect_error)`
2. Substituir `$conn->query()` por `$pdo->query()` ou `$pdo->exec()`
3. Substituir `$conn->real_escape_string()` por `$pdo->quote()` ou prepared statements
4. Substituir `$conn->error` por `$pdo->errorInfo()[2]`
5. Substituir `$conn->close()` por `$pdo = null`
6. Ajustar prepared statements MySQLi para PDO

## Progresso:
