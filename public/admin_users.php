<?php
// FILE: admin_users.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include '../config/db.php';
include '../views/header.php';

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>

<h2>Manajemen Pengguna</h2>
<a href="add_user.php" class="btn btn-success mb-3">+ Tambah User</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../views/footer.php'; ?>