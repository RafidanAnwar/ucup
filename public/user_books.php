<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

include '../config/db.php';
include '../views/header.php';

$books = $pdo->query("SELECT books.*, genres.name AS genre FROM books LEFT JOIN genres ON books.id_genre = genres.id_genre")->fetchAll();
?>

<h2>Daftar Buku Digital</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Kategori</th>
            <th>Tahun</th>
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
                <td><?= $book['genre'] ?></td>
                <td>
                    <a href="beli_ebook.php?id=<?= $book['id_book'] ?>" class="btn btn-sm btn-success">Beli</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../views/footer.php'; ?>