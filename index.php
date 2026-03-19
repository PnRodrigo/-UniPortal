<?php
session_start();

if (!isset($_SESSION['user_login'])) {
    header("Location: login/login.php");
    exit();
}

$grupo = $_SESSION['user_grupo'];

// Redirecionamento baseado no perfil (Role Based Access Control)
switch ($grupo) {
    case 1: // Gestor Pedagógico
        header("Location: admin/dashboard.php");
        break;
    case 2: // Aluno
        header("Location: aluno/dashboard.php");
        break;
    case 3: // Funcionário Serviços Académicos
        header("Location: funcionario/dashboard.php");
        break;
    default:
        session_destroy();
        header("Location: login/login.php?erro=2");
        break;
}
exit();
?>