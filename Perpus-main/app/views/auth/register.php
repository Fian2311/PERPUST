<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Perpustakaan Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --bg: #0f172a;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            color: #f8fafc;
        }

        /* Ambient Background shapes */
        .shape {
            position: absolute;
            filter: blur(80px);
            z-index: 0;
            border-radius: 50%;
        }
        .shape-1 { width: 400px; height: 400px; background: rgba(99, 102, 241, 0.2); top: -100px; left: -100px; }
        .shape-2 { width: 300px; height: 300px; background: rgba(168, 85, 247, 0.2); bottom: -50px; right: -50px; }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 48px;
            border-radius: 24px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            z-index: 10;
            animation: fadeIn 0.6s ease-out;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-icon {
            font-size: 40px;
            margin-bottom: 16px;
            display: inline-block;
            background: linear-gradient(135deg, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        p.subtitle {
            font-size: 14px;
            color: #94a3b8;
            margin-top: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #cbd5e1;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-size: 15px;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        }

        button {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 12px;
        }

        button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .error-msg {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            text-align: center;
            margin-bottom: 24px;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .footer {
            margin-top: 32px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
        }
        
        /* Custom scrollbar for card if needed */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.4); }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="login-card">
        <div class="header">
            <span class="logo-icon">✨</span>
            <h2>Daftar Akun Baru</h2>
            <p class="subtitle">Bergabung ke perpustakaan kami</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?url=auth/register">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" autocomplete="name" required placeholder="Budi Santoso">
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" autocomplete="email" required placeholder="budi@siswa.id">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Buat password (min. 6 karakter)">
            </div>

            <button type="submit" name="register">Buat Akun Sekarang</button>
            
            <p style="text-align:center; margin-top:20px; color:#94a3b8; font-size:14px;">
                Sudah memiliki akun? <a href="index.php?url=auth/login" style="color:var(--primary); text-decoration:none; font-weight: 500;">Masuk di sini</a>
            </p>
        </form>

        <div class="footer">
            &copy; 2026 Sistem Perpustakaan Modern
        </div>
    </div>
</body>
</html>
