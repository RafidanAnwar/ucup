<?php
// FILE: add_user.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $role]);

    header('Location: admin_users.php');
    exit;
}

include '../views/header.php';
?>

<h2>Tambah User</h2>
<form method="post">
    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-select" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="admin_users.php" class="btn btn-secondary">Batal</a>
</form>

<?php include '../views/footer.php'; ?>