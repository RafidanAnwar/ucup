<?php
// FILE: edit_user.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include '../config/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User tidak ditemukan.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
        $stmt->execute([$username, $password, $role, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $stmt->execute([$username, $role, $id]);
    }

    header('Location: admin_users.php');
    exit;
}

include '../views/header.php';
?>

<h2>Edit User</h2>
<form method="post">
    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>"
            required>
    </div>
    <div class="mb-3">
        <label>Password (biarkan kosong jika tidak ingin mengubah)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-select" required>
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="admin_users.php" class="btn btn-secondary">Batal</a>
</form>

<?php include '../views/footer.php'; ?>