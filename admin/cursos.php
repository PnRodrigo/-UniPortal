<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['user_login']) || $_SESSION['user_grupo'] != 1) {
    header("Location: ../login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $nome = trim($_POST['nome']);
        $stmt = $pdo->prepare("INSERT INTO cursos (nome_c) VALUES (?)");
        $stmt->execute([$nome]);
    } elseif (isset($_POST['toggle_status'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $new_status = ($status == 1) ? 0 : 1;
        $stmt = $pdo->prepare("UPDATE cursos SET ativo = ? WHERE id = ?");
        $stmt->execute([$new_status, $id]);
    }
    header("Location: cursos.php");
    exit();
}

$cursos = $pdo->query("SELECT * FROM cursos ORDER BY nome_c ASC")->fetchAll();
$page_title = "Gestão de Cursos - UniPortal";
include '../includes/header.php';
?>

<div class="container">
    <div class="card" style="margin-bottom: 2rem;">
        <h3>Novo Curso</h3>
        <form method="POST" style="display: flex; gap: 1rem; margin-top: 1rem;">
            <input type="text" name="nome" placeholder="Nome do Curso (ex: Engenharia Multimédia)" required
                style="flex: 1; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color);">
            <button type="submit" name="add" class="btn btn-primary">Criar Curso</button>
        </form>
    </div>

    <div class="card">
        <h3>Lista de Cursos</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Curso</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursos as $c): ?>
                    <tr>
                        <td>#
                            <?php echo $c['id']; ?>
                        </td>
                        <td style="font-weight: 600;">
                            <?php echo htmlspecialchars($c['nome_c']); ?>
                        </td>
                        <td>
                            <span class="badge"
                                style="background: <?php echo $c['ativo'] ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)'; ?>; color: <?php echo $c['ativo'] ? 'var(--success)' : 'var(--danger)'; ?>; border: 1px solid <?php echo $c['ativo'] ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)'; ?>;">
                                <?php echo $c['ativo'] ? 'Ativo' : 'Inativo'; ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                <input type="hidden" name="status" value="<?php echo $c['ativo']; ?>">
                                <button type="submit" name="toggle_status" class="btn"
                                    style="padding: 0.4rem 0.8rem; font-size: 0.8rem; background: transparent; border: 1px solid var(--border-color); color: var(--text-main);">
                                    <?php echo $c['ativo'] ? 'Desativar' : 'Ativar'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>

</html>