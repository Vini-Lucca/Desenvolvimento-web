<?php
$page_title = 'Busca Avançada';
require_once 'includes/header.php';
require_once 'config/conexao.php';

// Inicializa variáveis
$resultados = [];
$params = [];
$where_clauses = [];

// Carrega dados para os filtros
$cursos = $pdo->query("SELECT id, nome_curso FROM cursos ORDER BY nome_curso")->fetchAll(PDO::FETCH_ASSOC);
$professores = $pdo->query("SELECT id, nome_completo FROM professores ORDER BY nome_completo")->fetchAll(PDO::FETCH_ASSOC);

// Se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET)) {
    $sql = "SELECT 
                a.nome_completo AS aluno_nome,
                c.nome_curso,
                p.nome_completo AS professor_nome,
                m.data_matricula,
                m.status
            FROM matriculas m
            JOIN alunos a ON m.aluno_id = a.id
            JOIN cursos c ON m.curso_id = c.id
            JOIN professores p ON m.professor_id = p.id
            WHERE 1=1";

    // Filtro 1: por Curso
    if (!empty($_GET['curso_id'])) {
        $where_clauses[] = "m.curso_id = :curso_id";
        $params[':curso_id'] = $_GET['curso_id'];
    }

    // Filtro 2: por Professor
    if (!empty($_GET['professor_id'])) {
        $where_clauses[] = "m.professor_id = :professor_id";
        $params[':professor_id'] = $_GET['professor_id'];
    }

    // Filtro 3: por Status da Matrícula
    if (!empty($_GET['status'])) {
        $where_clauses[] = "m.status = :status";
        $params[':status'] = $_GET['status'];
    }

    if (!empty($where_clauses)) {
        $sql .= " AND " . implode(" AND ", $where_clauses);
    }
    
    $sql .= " ORDER BY c.nome_curso, a.nome_completo";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Busca Avançada de Matrículas</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-filter me-2"></i>Filtros de Busca</h6>
        </div>
        <div class="card-body">
            <form action="busca.php" method="GET">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="curso_id" class="form-label">Filtrar por Curso</label>
                        <select name="curso_id" id="curso_id" class="form-select">
                            <option value="">Todos os Cursos</option>
                            <?php foreach ($cursos as $curso): ?>
                                <option value="<?php echo $curso['id']; ?>" <?php echo (isset($_GET['curso_id']) && $_GET['curso_id'] == $curso['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($curso['nome_curso']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="professor_id" class="form-label">Filtrar por Professor</label>
                        <select name="professor_id" id="professor_id" class="form-select">
                            <option value="">Todos os Professores</option>
                            <?php foreach ($professores as $prof): ?>
                                <option value="<?php echo $prof['id']; ?>" <?php echo (isset($_GET['professor_id']) && $_GET['professor_id'] == $prof['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($prof['nome_completo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Filtrar por Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos os Status</option>
                            <option value="Cursando" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Cursando') ? 'selected' : ''; ?>>Cursando</option>
                            <option value="Concluído" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Concluído') ? 'selected' : ''; ?>>Concluído</option>
                            <option value="Cancelado" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end mb-3">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET)): ?>
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Resultados da Busca</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Curso</th>
                            <th>Professor</th>
                            <th>Data da Matrícula</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($resultados) > 0): ?>
                            <?php foreach ($resultados as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['aluno_nome']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nome_curso']); ?></td>
                                    <td><?php echo htmlspecialchars($row['professor_nome']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['data_matricula'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['status'] == 'Cursando' ? 'primary' : ($row['status'] == 'Concluído' ? 'success' : 'danger'); ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Nenhum resultado encontrado para os filtros selecionados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
