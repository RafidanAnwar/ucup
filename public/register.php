<?php
include '../config/db.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (strlen($username) < 4) {
        $error = "Username minimal 4 karakter.";
    } elseif ($password !== $confirm) {
        $error = "Password dan konfirmasi tidak cocok.";
    } else {
        // Cek username apakah sudah ada
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$username]);

        if ($check->rowCount() > 0) {
            $error = "Username sudah digunakan.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            $stmt->execute([$username, $hashed]);
            $success = "Registrasi berhasil! Silakan login.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Registrasi Akun</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e8f5e9, #ffffff);
            font-family: 'Segoe UI', sans-serif;
        }

        .register-box {
            max-width: 450px;
            margin: 80px auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .register-box h2 {
            margin-bottom: 25px;
        }

        .btn-login {
            margin-top: 15px;
            display: block;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="register-box">
        <h2 class="text-center text-success">Registrasi Akun</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
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

            <div class="mb-3">
                <label for="confirm" class="form-label">Konfirmasi Password</label>
                <input type="password" name="confirm" id="confirm" class="form-control" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success">Daftar</button>
            </div>
        </form>

        <a href="login.php" class="btn-login text-muted">Sudah punya akun? Login di sini</a>
    </div>

</body>

</html>