<?php
session_start();
include '../config/koneksi.php';

if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("location: ../dashboard.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['username']  = $data['username'];
        $_SESSION['nama_user'] = $data['nama_user'];
        $_SESSION['status']    = "login";

        header("location: ../dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Inventaris Barang</title>
    
    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #09090b;
            --bg-card: #18181b;
            --bg-input: #121215;
            --border-card: rgba(255, 255, 255, 0.08);
            --primary-red: #dc2626;
            --primary-red-hover: #b91c1c;
            --text-main: #f4f4f5;
            --text-muted: #a1a1aa;
            --text-dim: #71717a;
            --font-family: 'Poppins', sans-serif;
        }

        * {
            box-sizing: border-box;
            font-family: var(--font-family);
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Kartu Login Presisi di Tengah Layar */
        .login-card {
            width: 100%;
            max-width: 400px;
            background-color: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            padding: 30px 28px;
        }

        /* macOS Window Dots Sederhana */
        .mac-dots {
            display: flex;
            gap: 6px;
            margin-bottom: 22px;
        }

        .mac-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .mac-dot-red { background-color: #ff5f56; }
        .mac-dot-yellow { background-color: #ffbd2e; }
        .mac-dot-green { background-color: #27c93f; }

        /* Header Login */
        .login-header {
            margin-bottom: 24px;
        }

        .login-header h1 {
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 13px;
            line-height: 1.4;
        }

        /* Pesan Error */
        .alert-error {
            background-color: rgba(220, 38, 38, 0.12);
            border: 1px solid rgba(220, 38, 38, 0.3);
            border-radius: 6px;
            padding: 10px 14px;
            color: #fca5a5;
            font-size: 12.5px;
            margin-bottom: 18px;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 13px;
            color: #d4d4d8;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            background-color: var(--bg-input);
            border: 1px solid var(--border-card);
            border-radius: 6px;
            font-size: 13.5px;
            color: #ffffff;
            outline: none;
            transition: border-color 0.2s, background-color 0.2s;
            font-family: var(--font-family);
        }

        .form-group input:focus {
            border-color: var(--primary-red);
            background-color: #16161b;
        }

        .form-group input::placeholder {
            color: #52525b;
        }

        /* Autofill Dark Mode Fix */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #ffffff !important;
            -webkit-box-shadow: 0 0 0px 1000px #27272a inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* Tombol Login */
        .btn-submit {
            width: 100%;
            padding: 11px;
            background-color: var(--primary-red);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 4px;
        }

        .btn-submit:hover {
            background-color: var(--primary-red-hover);
        }

        /* Link Kembali */
        .btn-back {
            display: block;
            text-align: center;
            color: var(--text-dim);
            text-decoration: none;
            font-size: 13px;
            margin-top: 18px;
            transition: color 0.2s;
        }

        .btn-back:hover {
            color: #ffffff;
        }

        /* Footer */
        .login-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 11.5px;
            color: var(--text-dim);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 16px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- macOS Window Dots -->
        <div class="mac-dots">
            <span class="mac-dot mac-dot-red"></span>
            <span class="mac-dot mac-dot-yellow"></span>
            <span class="mac-dot mac-dot-green"></span>
        </div>

        <div class="login-header">
            <h1>Login Admin</h1>
            <p>Masukkan username dan password Anda</p>
        </div>

        <?php if (isset($error)) { ?>
            <div class="alert-error">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required autocomplete="off" placeholder="Masukkan username">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Masukkan password">
            </div>

            <button type="submit" name="login" class="btn-submit">Masuk</button>
        </form>

        <!-- Link Navigasi Kembali ke Katalog Publik -->
        <a href="../index.php" class="btn-back">&larr; Kembali ke Katalog Publik</a>

        <div class="login-footer">
            &copy; <?php echo date('Y'); ?> Sistem Inventaris Barang. All rights reserved.
        </div>
    </div>

</body>
</html>
