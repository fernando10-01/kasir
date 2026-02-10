<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir Fernando</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1C4D8D;
            --secondary-color: #4988C4;
            --accent-color: #BDE8F5;
            --dark-color: #0F2854;
        }

        body {
            background: radial-gradient(circle at top right, var(--accent-color), var(--secondary-color)), 
                        linear-gradient(135deg, #BDE8F5 0%, #4988C4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            overflow: hidden;
        }

        /* Dekorasi Lingkaran Background */
        .bg-decoration {
            position: absolute;
            width: 400px;
            height: 400px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.2;
            animation: float 10s infinite alternate;
        }

        @keyframes float {
            from { transform: translate(-10%, -10%); }
            to { transform: translate(10%, 10%); }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(189, 232, 245, 0.5);
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(15, 40, 84, 0.15);
            width: 100%;
            max-width: 420px;
            padding: 3rem 2.5rem;
            transition: all 0.4s ease;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-logo {
            width: 80px; 
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            transform: rotate(-10deg);
            box-shadow: 0 10px 20px rgba(28, 77, 141, 0.4);
        }

        .brand-title {
            font-weight: 800;
            color: var(--dark-color);
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
        }

        .brand-subtitle {
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .form-label {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--primary-color);
            margin-left: 5px;
        }

        .input-group {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            border: 1.5px solid var(--accent-color);
            transition: all 0.3s;
        }

        .input-group:focus-within {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 4px rgba(73, 136, 196, 0.1);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: var(--secondary-color);
            padding-left: 1.2rem;
        }

        .form-control {
            border: none;
            padding: 14px 15px;
            font-weight: 500;
            background: transparent;
            color: var(--dark-color);
        }

        .form-control:focus {
            box-shadow: none;
            background: transparent;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 15px;
            padding: 14px;
            font-weight: 800;
            color: white;
            letter-spacing: 1px;
            margin-top: 1rem;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(28, 77, 141, 0.4);
            background: linear-gradient(135deg, var(--dark-color), var(--primary-color));
        }

        .alert-gagal {
            background: #fff1f2;
            color: #e11d48;
            border: 1px solid #fecdd3;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .toggle-password {
            cursor: pointer;
            padding-right: 1.2rem;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            transition: 0.3s;
        }
        
        .toggle-password:hover { 
            color: var(--primary-color); 
        }

        .footer-text {
            font-size: 10px; 
            font-weight: 700; 
            color: var(--secondary-color); 
            letter-spacing: 2px; 
            text-transform: uppercase;
            opacity: 0.6;
        }
    </style>
</head>
<body>

<div class="bg-decoration"></div>

<div class="container d-flex justify-content-center p-3">
    <div class="login-card">
        <div class="brand-logo">
            <i class="fas fa-cash-register"></i>
        </div>

        <div class="text-center mb-4">
            <h2 class="brand-title">KASIR FERNANDO</h2>
            <p class="brand-subtitle">Silakan masuk untuk mengelola toko</p>
        </div>

        <?php if(isset($_GET['pesan']) && $_GET['pesan'] == "gagal"): ?>
            <div class="alert alert-gagal text-center py-2 mb-4 animate__animated animate__shakeX">
                <i class="fas fa-circle-exclamation me-2"></i> Username / Password Salah!
            </div>
        <?php endif; ?>

        <form action="auth.php" method="POST">
            <div class="mb-3">
                <label class="form-label text-uppercase">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autocomplete="off">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-uppercase">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-shield-halved"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••••" required>
                    <span class="toggle-password" onclick="togglePass()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100 shadow">
                MASUK KE SISTEM <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </form>

        <div class="text-center mt-5">
            <p class="footer-text">
                &copy; 2026 Kasir Fernando SMK 2 PEKANBARU
            </p>
        </div>
    </div>
</div>

<script>
    // Fungsi Toggle Password
    function togglePass() {
        const input = document.getElementById("passwordInput");
        const icon = document.getElementById("eyeIcon");
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.replace("fa-eye-slash", "fa-eye");
        }
    }
</script>

</body>
</html>