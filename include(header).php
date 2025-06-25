<?php
// Inicia a sessão em todas as páginas que incluem este header
session_start();

// Verifica se o usuário está logado. Se não, redireciona para a página de login.
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /S.O-trabalho2/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- O título será definido em cada página específica -->
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Sistema Acadêmico'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- CSS Customizado (opcional) -->
    <style>
        body { background-color: #f8f9fa; }
        .wrapper { display: flex; }
        #sidebar { min-width: 250px; max-width: 250px; background: #343a40; color: #fff; transition: all 0.3s; }
        #sidebar.active { margin-left: -250px; }
        #sidebar .sidebar-header { padding: 20px; background: #495057; }
        #sidebar ul.components { padding: 20px 0; }
        #sidebar ul p { color: #fff; padding: 10px; }
        #sidebar ul li a { padding: 10px; font-size: 1.1em; display: block; color: #adb5bd; }
        #sidebar ul li a:hover { color: #fff; background: #495057; }
        #content { width: 100%; padding: 20px; min-height: 100vh; transition: all 0.3s; }
        .card-header-custom { background-color: #0d6efd; color: white; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-buildings-fill"></i> S. Acadêmico</h3>
            </div>

            <ul class="list-unstyled components">
                <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <a href="/sistema-academico/index.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                </li>
                <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'busca.php' ? 'active' : ''; ?>">
                    <a href="/sistema-academico/busca.php"><i class="bi bi-search me-2"></i>Busca Avançada</a>
                </li>
                <li>
                    <a href="#gestaoSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="bi bi-pencil-square me-2"></i>Gerenciamento
                    </a>
                    <ul class="collapse list-unstyled" id="gestaoSubmenu">
                        <li><a href="/sistema-academico/modulos/alunos/">Alunos</a></li>
                        <li><a href="/sistema-academico/modulos/professores/">Professores</a></li>
                        <li><a href="/sistema-academico/modulos/cursos/">Cursos</a></li>
                        <li><a href="/sistema-academico/modulos/matriculas/">Matrículas</a></li>
                    </ul>
                </li>
                <li>
                     <a href="/sistema-academico/relatorios/relatorio_matriculas_curso.php"><i class="bi bi-file-earmark-pdf-fill me-2"></i>Relatórios</a>
                </li>
                 <li>
                    <a href="/sistema-academico/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="bi bi-list"></i>
                    </button>
                     <span class="navbar-text ms-auto">
                        Bem-vindo, <strong><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></strong>!
                    </span>
                </div>
            </n