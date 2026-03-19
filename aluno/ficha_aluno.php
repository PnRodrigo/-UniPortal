<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['user_login']) || $_SESSION['user_grupo'] != 2) {
    header("Location: ../login/login.php");
    exit();
}

$user = $_SESSION['user_login'];

// Procurar ficha existente
$stmt = $pdo->prepare("SELECT * FROM fichas_aluno WHERE user_login = ?");
$stmt->execute([$user]);
$ficha = $stmt->fetch();

// Lista de cursos para o SELECT
$cursos = $pdo->query("SELECT * FROM cursos WHERE ativo = 1")->fetchAll();

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome_completo']);
    $curso_id = $_POST['curso_id'];
    $submeter = isset($_POST['submit_button']);
    $estado = ($submeter) ? 'Submetida' : 'Rascunho';

    $foto_path = $ficha['foto'] ?? 'uploads/placeholder.svg';

    // RF3.1 & RNF4 - Upload de fotografia
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['foto']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed) && $_FILES['foto']['size'] <= 2097152) { // Max 2MB
            $novo_nome = "foto_" . $user . "_" . time() . "." . $ext;
            $destino = "../uploads/" . $novo_nome;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $foto_path = "uploads/" . $novo_nome;
            }
        }
    }

    if ($ficha) {
        // Update - Só permite editar se for rascunho ou rejeitada (lógica comum)
        $stmt = $pdo->prepare("UPDATE fichas_aluno SET nome_completo = ?, curso_id = ?, foto = ?, estado = ? WHERE user_login = ?");
        $stmt->execute([$nome, $curso_id, $foto_path, $estado, $user]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO fichas_aluno (user_login, nome_completo, curso_id, foto, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user, $nome, $curso_id, $foto_path, $estado]);
    }
    header("Location: dashboard.php");
    exit();
}

$page_title = "Minha Ficha - UniPortal";
include '../includes/header.php';
?>

<div class="container" style="max-width: 800px;">
    <div class="card">
        <h2 style="margin-bottom: 2rem;"><i class="fas fa-user-edit" style="color: var(--primary);"></i> Dados da Ficha
            de Aluno</h2>

        <?php if ($ficha && $ficha['estado'] == 'Aprovada'): ?>
            <div
                style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
                <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                <div>
                    <strong>Ficha Aprovada!</strong><br>
                    Os seus dados foram validados e já não podem ser alterados.
                </div>
            </div>
        <?php else: ?>

            <form method="POST" enctype="multipart/form-data">
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
                    <div style="text-align: center;">
                        <label
                            style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); margin-bottom: 1rem;">FOTOGRAFIA</label>
                        <div style="position: relative; display: inline-block;">
                            <img src="../<?php echo $ficha['foto'] ?? 'uploads/placeholder.svg'; ?>"
                                style="width: 150px; height: 150px; border-radius: 20px; object-fit: cover; border: 2px solid var(--border-color);">
                            <input type="file" name="foto" style="margin-top: 1rem; font-size: 0.8rem; width: 100%;">
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nome Completo</label>
                            <input type="text" name="nome_completo" required
                                value="<?php echo htmlspecialchars($ficha['nome_completo'] ?? ''); ?>"
                                style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid var(--border-color);">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Curso Pretendido</label>
                            <select name="curso_id" required
                                style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid var(--border-color);">
                                <option value="">Selecione um curso...</option>
                                <?php foreach ($cursos as $c): ?>
                                    <option value="<?php echo $c['id']; ?>" <?php echo ($ficha && $ficha['curso_id'] == $c['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($c['nome_c']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div
                    style="margin-top: 3rem; display: flex; gap: 1rem; border-top: 1px solid var(--border-color); padding-top: 2rem;">
                    <button type="submit" name="save_button" class="btn"
                        style="background: transparent; border: 1px solid var(--border-color); color: var(--text-main); flex: 1; justify-content: center;">Guardar
                        Rascunho</button>
                    <button type="submit" name="submit_button" class="btn btn-primary"
                        style="flex: 2; justify-content: center; font-size: 1.1rem;">
                        Submeter para Validação <i class="fas fa-paper-plane" style="margin-left: 0.5rem;"></i>
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>

</html>