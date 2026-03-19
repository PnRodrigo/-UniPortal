<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - UniPortal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/cursos/style.css?v=<?php echo time(); ?>">
    <script>
        if (localStorage.getItem('theme') === 'light') document.documentElement.classList.add('light');
    </script>
    <style>
        body {
            background: var(--bg-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
            transition: background 0.3s ease;
        }

        .login-card {
            background: var(--card-bg);
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 420px;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div
                style="background: var(--primary); width: 64px; height: 64px; border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);">
                <i class="fas fa-university" style="font-size: 1.8rem; color: white;"></i>
            </div>
            <h1
                style="color: var(--text-main); margin: 0; font-size: 1.875rem; font-weight: 800; letter-spacing: -0.025em;">
                Registo Aluno</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">Crie a sua conta no UniPortal
            </p>
        </div>

        <form action="registro_process.php" method="POST">
            <div class="form-group">
                <label>Utilizador (Login)</label>
                <input type="text" name="login" required placeholder="ex: joao_silva">
            </div>
            <div class="form-group" style="margin-bottom: 2rem;">
                <label>Palavra-passe</label>
                <input type="password" name="pwd" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary"
                style="width: 100%; padding: 0.875rem; border-radius: 12px; font-weight: 700;">
                Criar Conta Agora <i class="fas fa-user-plus" style="margin-left: 0.5rem;"></i>
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; font-size: 0.875rem; color: var(--text-muted);">
            Já tem conta? <a href="login.php"
                style="color: var(--primary); font-weight: 700; text-decoration: none;">Iniciar Sessão</a>
        </div>
    </div>
</body>

</html>