<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO books (title, author, category, year, harga, id_genre, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['title'],
        $_POST['author'],
        $_POST['category'],
        $_POST['year'],
        $_POST['harga'],
        $_POST['id_genre'],
        $_POST['description']
    ]);
    header("Location: books.php");
    exit;
}

$genres = $pdo->query("SELECT * FROM genres")->fetchAll();

include '../views/header.php';
?>

<h2>Tambah Buku</h2>
<form method="post">
    <input name="title" class="form-control mb-2" placeholder="Judul Buku" required>
    <input name="author" class="form-control mb-2" placeholder="Penulis" required>
    <input name="category" class="form-control mb-2" placeholder="Kategori">
    <input name="year" type="number" class="form-control mb-2" placeholder="Tahun Terbit">
    <input name="harga" type="number" step="0.01" class="form-control mb-2" placeholder="Harga (Rp)" required>

    <select name="id_genre" class="form-control mb-2" required>
        <option value="">Pilih Genre</option>
        <?php foreach ($genres as $genre): ?>
            <option value="<?= $genre['id_genre'] ?>"><?= htmlspecialchars($genre['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <textarea name="description" class="form-control mb-2" placeholder="Deskripsi Buku"></textarea>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="books.php" class="btn btn-secondary">Batal</a>
</form>

<?php include '../views/footer.php'; ?>