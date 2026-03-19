<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['user_login']) || $_SESSION['user_grupo'] != 1) {
    header("Location: ../login/login.php");
    exit();
}

$curso_id = $_GET['curso_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_plano'])) {
    $c_id = $_POST['curso_id'];
    $u_id = $_POST['uc_id'];
    $ano = $_POST['ano'];
    $sem = $_POST['semestre'];

    $stmt = $pdo->prepare("INSERT IGNORE INTO plano_estudos (curso_id, disciplina_id, ano, semestre) VALUES (?, ?, ?, ?)");
    $stmt->execute([$c_id, $u_id, $ano, $sem]);
    header("Location: planos.php?curso_id=$c_id");
    exit();
}

$cursos = $pdo->query("SELECT * FROM cursos WHERE ativo = 1")->fetchAll();
$ucs = $pdo->query("SELECT * FROM disciplinas ORDER BY nome_d")->fetchAll();

$plano = [];
if ($curso_id) {
    $stmt = $pdo->prepare("SELECT p.*, d.nome_d FROM plano_estudos p JOIN disciplinas d ON p.disciplina_id = d.id WHERE p.curso_id = ? ORDER BY ano, semestre");
    $stmt->execute([$curso_id]);
    $plano = $stmt->fetchAll();
}

$page_title = "Plano de Estudos - UniPortal";
include '../includes/header.php';
?>

<div class="container">
    <div class="card" style="margin-bottom: 2rem;">
        <h3>Selecionar Curso para Configurar</h3>
        <form method="GET" style="display: flex; gap: 1rem; margin-top: 1rem;">
            <select name="curso_id" onchange="this.form.submit()"
                style="flex: 1; padding: 0.75rem; border-radius: 8px;">
                <option value="">Selecione um curso...</option>
                <?php foreach ($cursos as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo ($curso_id == $c['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c['nome_c']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if ($curso_id): ?>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
            <div class="card">
                <h4>Adicionar UC ao Plano</h4>
                <form method="POST" style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem;">
                    <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
                    <select name="uc_id" required style="padding: 0.6rem;">
                        <?php foreach ($ucs as $u): ?>
                            <option value="<?php echo $u['id']; ?>">
                                <?php echo htmlspecialchars($u['nome_d']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="number" name="ano" placeholder="Ano" min="1" max="5" required
                            style="flex: 1; padding: 0.6rem;">
                        <input type="number" name="semestre" placeholder="Sem" min="1" max="2" required
                            style="flex: 1; padding: 0.6rem;">
                    </div>
                    <button type="submit" name="add_plano" class="btn btn-primary">Vincular</button>
                </form>
            </div>

            <div class="card">
                <h4>Matriz Curricular</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Ano/Sem</th>
                            <th>Disciplina</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plano as $p): ?>
                            <tr>
                                <td>
                                    <?php echo $p['ano']; ?>º /
                                    <?php echo $p['semestre']; ?>º
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($p['nome_d']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>

</html>