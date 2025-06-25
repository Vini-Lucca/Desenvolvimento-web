<?php
// Este é um exemplo completo para o CRUD de Alunos.
// Você deve replicar essa estrutura para Professores, Cursos e Matrículas.

$page_title = 'Gerenciar Alunos';
// O caminho para os includes e config deve ser ajustado
require_once '../../includes/header.php';
require_once '../../config/conexao.php';

// Deletar aluno se o ID for passado
if (isset($_GET['delete_id'])) {
    $id_para_deletar = $_GET['delete_id'];
    try {
        // Primeiro, deletar matrículas associadas para evitar erro de chave estrangeira
        $sql_delete_matriculas = "DELETE FROM matriculas WHERE aluno_id = :id";
        $stmt_delete_matriculas = $pdo->prepare($sql_delete_matriculas);
        $stmt_delete_matriculas->execute([':id' => $id_para_deletar]);

        // Agora, deletar o aluno
        $sql_delete_aluno = "DELETE FROM alunos WHERE id = :id";
        $stmt_delete_aluno = $pdo->prepare($sql_delete_aluno);
        $stmt_delete_aluno->execute([':id' => $id_para_deletar]);
        
        // Redireciona para a mesma página para mostrar a lista atualizada
        header("Location: index.php?status=deletado");
        exit;
    } catch (PDOException $e) {
        header("Location: index.php?status=erro_delete");
        exit;
    }
}

// Lógica de CREATE (Adicionar) e UPDATE (Editar)
$aluno = null;
$form_action = 'index.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $nome_completo = trim($_POST['nome_completo']);
    $email = trim($_POST['email']);
    $data_nascimento = $_POST['data_nascimento'];
    $telefone = trim($_POST['telefone']);

    // Validação simples
    if (empty($nome_completo) || empty($email)) {
        $error_message = "Nome e E-mail são obrigatórios.";
    } else {
        try {
            if ($id) { // UPDATE
                $sql = "UPDATE alunos SET nome_completo = :nome, email = :email, data_nascimento = :data, telefone = :tel WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['nome' => $nome_completo, 'email' => $email, 'data' => $data_nascimento, 'tel' => $telefone, 'id' => $id]);
                header("Location: index.php?status=editado");
            } else { // CREATE
                $sql = "INSERT INTO alunos (nome_completo, email, data_nascimento, telefone) VALUES (:nome, :email, :data, :tel)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['nome' => $nome_completo, 'email' => $email, 'data' => $data_nascimento, 'tel' => $telefone]);
                header("Location: index.php?status=adicionado");
            }
            exit;
        } catch (PDOException $e) {
            $error_message = "Erro ao salvar aluno: " . $e->getMessage();
        }
    }
}

// Preencher formulário para edição
if (isset($_GET['edit_id'])) {
    $id_para_editar = $_GET['edit_id'];
    $sql = "SELECT * FROM alunos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id_para_editar]);
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);
    $form_action = 'index.php?edit_id=' . $id_para_editar;
}

// Buscar todos os alunos para a listagem
$alunos = $pdo->query("SELECT * FROM alunos ORDER BY nome_completo ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header card-header-custom">
            <h4 class="mb-0">
                <?php echo $aluno ? '<i class="bi bi-pencil-fill me-2"></i>Editando Aluno' : '<i class="bi bi-person-plus-fill me-2"></i>Adicionar Novo Aluno'; ?>
            </h4>
        </div>
        <div class="card-body">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <form action="<?php echo $form_action; ?>" method="POST">
                <input type="hidden" name="id" value="<?php echo $aluno['id'] ?? ''; ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nome_completo" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nome_completo" name="nome_completo" value="<?php echo htmlspecialchars($aluno['nome_completo'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($aluno['email'] ?? ''); ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($aluno['data_nascimento'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($aluno['telefone'] ?? ''); ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle-fill me-2"></i>Salvar</button>
                <?php if ($aluno): ?>
                    <a href="index.php" class="btn btn-secondary">Cancelar Edição</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <hr class="my-4">

    <div class="card shadow">
        <div class="card-header">
            <h4 class="mb-0"><i class="bi bi-list-ul me-2"></i>Lista de Alunos</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome Completo</th>
                            <th>E-mail</th>
                            <th>Data de Nascimento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($alunos) > 0): ?>
                            <?php foreach ($alunos as $al): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($al['id']); ?></td>
                                    <td><?php echo htmlspecialchars($al['nome_completo']); ?></td>
                                    <td><?php echo htmlspecialchars($al['email']); ?></td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($al['data_nascimento']))); ?></td>
                                    <td>
                                        <a href="index.php?edit_id=<?php echo $al['id']; ?>" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="index.php?delete_id=<?php echo $al['id']; ?>" class="btn btn-sm btn-danger btn-delete" title="Excluir"><i class="bi bi-trash-fill"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Nenhum aluno cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>
