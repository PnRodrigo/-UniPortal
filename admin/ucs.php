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
        $stmt = $pdo->prepare("INSERT INTO disciplinas (nome_d) VALUES (?)");
        $stmt->execute([$nome]);
    }
    header("Location: ucs.php");
    exit();
}

$ucs = $pdo->query("SELECT * FROM disciplinas ORDER BY nome_d ASC")->fetchAll();
$page_title = "Disciplinas - UniPortal";
include '../includes/header.php';
?>

<div class="container" style="max-width: 800px;">
    <div class="card" style="margin-bottom: 2rem;">
        <h3>Adicionar Disciplina (UC)</h3>
        <form method="POST" style="display: flex; gap: 1rem; margin-top: 1rem;">
            <input type="text" name="nome" placeholder="Nome da Disciplina..." required
                style="flex: 1; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color);">
            <button type="submit" name="add" class="btn btn-primary">Adicionar UC</button>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome da Unidade Curricular</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ucs as $u): ?>
                    <tr>
                        <td>
                            <?php echo $u['id']; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($u['nome_d']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>

</html>