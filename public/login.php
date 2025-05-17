<?php
include '../config/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;
    } else {
        $error = "Login gagal! Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Perpustakaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd, #ffffff);
            font-family: 'Segoe UI', sans-serif;
        }

        .login-box {
            max-width: 420px;
            margin: 80px auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .login-box h2 {
            margin-bottom: 25px;
        }

        .btn-register {
            margin-top: 15px;
            display: block;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2 class="text-center text-primary">Login Akun</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" novalidate>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Masuk</button>
            </div>
        </form>

        <a href="register.php" class="btn-register text-muted">Belum punya akun? Daftar sekarang</a>
    </div>

</body>

</html>