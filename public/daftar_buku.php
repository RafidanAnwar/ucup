<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include '../config/db.php';
include '../views/header.php';

$userRole = $_SESSION['role'];

// Ambil daftar buku
$books = $pdo->query("
    SELECT books.*, genres.name AS genre 
    FROM books 
    LEFT JOIN genres ON books.id_genre = genres.id_genre
")->fetchAll();
?>

<h2>Daftar Buku</h2>

<?php if ($userRole === 'admin'): ?>
    <a href="add_book.php" class="btn btn-success mb-3">+ Tambah Buku</a>
<?php endif; ?>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Kategori</th>
            <th>Tahun</th>
            <th>Harga</th>
            <th>Genre</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['category']) ?></td>
                <td><?= $book['year'] ?></td>
                <td>Rp <?= number_format($book['harga'], 0, ',', '.') ?></td>
                <td><?= $book['genre'] ?></td>
                <td><?= htmlspecialchars($book['description']) ?></td>
                <td>
                    <?php if ($userRole === 'admin'): ?>
                        <a href="edit_book.php?id=<?= $book['id_book'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_book.php?id=<?= $book['id_book'] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin hapus buku ini?')">Hapus</a>
                    <?php else: ?>
                        <a href="beli_ebook.php?id=<?= $book['id_book'] ?>" class="btn btn-primary btn-sm">Beli</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../views/footer.php'; ?>