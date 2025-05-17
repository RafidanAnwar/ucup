<?php
include '../config/db.php';
include '../views/header.php';

$books = $pdo->query("SELECT books.*, genres.name AS genre FROM books LEFT JOIN genres ON books.id_genre = genres.id_genre")->fetchAll();
?>

<h2>Daftar Buku</h2>
<a href="add_book.php" class="btn btn-success mb-3">+ Tambah Buku</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Kategori</th>
            <th>Tahun</th>
            <th>Harga</th>
            <th>Genre</th>
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
                <td>
                    <a href="edit_book.php?id=<?= $book['id_book'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_book.php?id=<?= $book['id_book'] ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../views/footer.php'; ?>