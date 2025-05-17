<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include '../config/db.php';
include '../views/header.php';

// Ambil semua genre
$genres = $pdo->query("SELECT * FROM genres ORDER BY name")->fetchAll();
?>

<h2>Manajemen Genre</h2>
<a href="add_genre.php" class="btn btn-success mb-3">+ Tambah Genre</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Genre</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($genres as $genre): ?>
            <tr>
                <td><?= htmlspecialchars($genre['name']) ?></td>
                <td>
                    <a href="edit_genre.php?id=<?= $genre['id_genre'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_genre.php?id=<?= $genre['id_genre'] ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin hapus genre ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../views/footer.php'; ?>