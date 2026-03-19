<?php
session_start();
if (isset($_SESSION['user_login']))
    header("Location: ../index.php");
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Seguro - UniPortal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/cursos/style.css?v=<?php echo time(); ?>">

    <style>
        body.login-wrap {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            transition: background 0.3s ease;
        }

        .login-card {
            background: var(--card-bg);
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 420px;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .auth-note {
            background: rgba(99, 102, 241, 0.05);
            padding: 1.25rem;
            border-radius: 16px;
            margin-top: 2rem;
            border: 1px dashed var(--primary);
        }

        .error-banner {
            animation: shake 0.5s cubic-bezier(.36, .07, .19, .97) both;
        }

        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
    </style>

    <script>
        if (localStorage.getItem('theme') === 'light') document.documentElement.classList.add('light');
    </script>
</head>

<body class="login-wrap">

    <div class="login-card">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <div
                style="background: var(--primary); width: 64px; height: 64px; border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);">
                <i class="fas fa-university" style="font-size: 1.8rem; color: white;"></i>
            </div>
            <h1
                style="color: var(--text-main); margin: 0; font-size: 1.875rem; font-weight: 800; letter-spacing: -0.025em;">
                UniPortal</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">Gestão Académica Centralizada
            </p>
        </div>

        <?php if (isset($_GET['erro'])): ?>
            <div class="error-banner"
                style="padding: 0.875rem; background: #fef2f2; color: #b91c1c; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 600; font-size: 0.85rem; border: 1px solid #fee2e2; text-align: center;">
                <i class="fas fa-exclamation-circle"></i> Credenciais inválidas.
            </div>
        <?php endif; ?>

        <form action="login_process.php" method="POST">
            <div class="form-group">
                <label for="login">Utilizador</label>
                <input type="text" id="login" name="login" placeholder="ex: admin" required autofocus>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="pwd">Palavra-passe</label>
                <input type="password" id="pwd" name="pwd" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem;">
                Entrar no Portal <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <div class="auth-note">
            <p
                style="font-size: 0.75rem; font-weight: 800; color: var(--primary); margin: 0 0 0.75rem 0; text-transform: uppercase; letter-spacing: 0.05em;">
                <i class="fas fa-unlock-alt"></i> Acesso de Teste:
            </p>
            <div
                style="display: grid; gap: 0.4rem; color: var(--text-muted); font-family: 'Courier New', Courier, monospace; font-size: 0.85rem;">
                <div><span style="color: var(--primary)">•</span> admin / password</div>
                <div><span style="color: var(--primary)">•</span> aluno / password</div>
                <div><span style="color: var(--primary)">•</span> funcionario / password</div>

            </div>
        </div>

        <div style="text-align: center; margin-top: 2rem; font-size: 0.875rem; color: var(--text-muted);">
            Não tem conta? <a href="registro.php"
                style="color: var(--primary); font-weight: 700; text-decoration: none; border-bottom: 2px solid transparent; transition: border 0.2s;">Criar
                conta de Aluno</a>
        </div>
    </div>

</body>

</html>