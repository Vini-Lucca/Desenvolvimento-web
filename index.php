<?php
$page_title = 'Dashboard';
// Inclui o header, que já faz a verificação de login
require_once 'include(header).php';
require_once 'config(conexao bd).php';

// Consultas para obter estatísticas para o dashboard
try {
    $total_alunos = $pdo->query("SELECT COUNT(*) FROM alunos")->fetchColumn();
    $total_cursos = $pdo->query("SELECT COUNT(*) FROM cursos")->fetchColumn();
    $total_professores = $pdo->query("SELECT COUNT(*) FROM professores")->fetchColumn();
    $total_matriculas = $pdo->query("SELECT COUNT(*) FROM matriculas WHERE status = 'Cursando'")->fetchColumn();
} catch (PDOException $e) {
    // Em caso de erro, define os totais como 'Erro'
    $total_alunos = $total_cursos = $total_professores = $total_matriculas = "Erro";
    $db_error = $e->getMessage();
}
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <?php if (isset($db_error)): ?>
        <div class="alert alert-danger">Erro ao carregar dados do dashboard: <?php echo htmlspecialchars($db_error); ?></div>
    <?php endif; ?>

    <!-- Cards de Estatísticas -->
    <div class="row">
        <!-- Card Alunos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Alunos Cadastrados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($total_alunos); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Professores -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Professores</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($total_professores); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-video3 fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card Cursos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Cursos Oferecidos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($total_cursos); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-journal-bookmark-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Matrículas Ativas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Matrículas Ativas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($total_matriculas); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-card-checklist fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bem-vindo ao Sistema de Gestão Acadêmica</h6>
                </div>
                <div class="card-body">
                    <p>Utilize o menu à esquerda para navegar pelas seções do sistema.</p>
                    <p>Você pode gerenciar alunos, professores, cursos e matrículas, além de gerar relatórios e realizar buscas detalhadas.</p>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
// Inclui o footer
require_once 'include(foader).php';
?>
