<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_login'])) {
    header("Location: login/login.php");
    exit();
}
$perfil = $_SESSION['user_grupo'];
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $page_title ?? 'UniPortal'; ?>
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/cursos/style.css?v=<?php echo time(); ?>">
    <script>
        function toggleTheme() {
            const isLight = document.documentElement.classList.toggle('light');
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
        }
        if (localStorage.getItem('theme') === 'light') document.documentElement.classList.add('light');
    </script>
</head>

<body>
    <nav>
        <div style="display: flex; align-items: center; gap: 0.8rem;">
            <div
                style="background: var(--primary); width: 35px; height: 35px; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.4);">
                <i class="fas fa-university" style="font-size: 1rem; color: white;"></i>
            </div>
            <div style="line-height: 1;">
                <div style="font-weight: 800; font-size: 1.1rem; color: var(--text-main);">UniPortal</div>
                <small style="color: var(--primary); font-size: 0.7rem; text-transform: uppercase; font-weight: 600;">
                    <?php
                    if ($perfil == 1)
                        echo "Gestão Pedagógica";
                    elseif ($perfil == 3)
                        echo "Serviços Académicos";
                    else
                        echo "Área do Aluno";
                    ?>
                </small>
            </div>
        </div>

        <ul class="nav-links">
            <li><a href="dashboard.php">Painel</a></li>

            <?php if ($perfil == 1): // GESTOR ?>
                <li><a href="cursos.php">Cursos & UCs</a></li>
                <li><a href="fichas_validar.php">Validar Fichas</a></li>
            <?php elseif ($perfil == 2): // ALUNO ?>
                <li><a href="ficha_aluno.php">Minha Ficha</a></li>
                <li><a href="matricula.php">Matrícula</a></li>
            <?php elseif ($perfil == 3): // STAFF ?>
                <li><a href="matriculas_admin.php">Gerir Matrículas</a></li>
                <li><a href="pautas.php">Pautas</a></li>
            <?php endif; ?>

            <li style="margin-left: 1rem; margin-top: 0.2rem;">
                <button onclick="toggleTheme()" class="btn" title="Alternar Tema" style="background: rgba(99, 102, 241, 0.1); padding: 0.5rem 0.75rem; color: var(--text-main); border: none; font-size: 1.1rem; box-shadow: none;">
                    <i class="fas fa-moon"></i>
                </button>
            </li>
            <li style="margin-left: 0.5rem;">
                <a href="../login/logout.php" class="btn btn-danger" style="padding: 0.5rem 1rem; color: white;">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </li>
        </ul>
    </nav>