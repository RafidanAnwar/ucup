<?php
include '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: books.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM books WHERE id_book = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    echo "Buku tidak ditemukan.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE books SET title=?, author=?, category=?, year=?, harga=?, id_genre=?, description=? WHERE id_book=?");
    $stmt->execute([
        $_POST['title'],
        $_POST['author'],
        $_POST['category'],
        $_POST['year'],
        $_POST['harga'],
        $_POST['id_genre'],
        $_POST['description'],
        $id
    ]);
    header("Location: books.php");
    exit;
}

$genres = $pdo->query("SELECT * FROM genres")->fetchAll();
include '../views/header.php';
?>

<h2>Edit Buku</h2>
<form method="post">
    <input name="title" class="form-control mb-2" value="<?= htmlspecialchars($book['title']) ?>" placeholder="Judul"
        required>
    <input name="author" class="form-control mb-2" value="<?= htmlspecialchars($book['author']) ?>"
        placeholder="Penulis" required>
    <input name="category" class="form-control mb-2" value="<?= htmlspecialchars($book['category']) ?>"
        placeholder="Kategori">
    <input name="year" type="number" class="form-control mb-2" value="<?= $book['year'] ?>" placeholder="Tahun Terbit">
    <input name="harga" type="number" step="0.01" class="form-control mb-2" value="<?= $book['harga'] ?>"
        placeholder="Harga (Rp)" required>

    <select name="id_genre" class="form-control mb-2" required>
        <option value="">Pilih Genre</option>
        <?php foreach ($genres as $genre): ?>
            <option value="<?= $genre['id_genre'] ?>" <?= $book['id_genre'] == $genre['id_genre'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($genre['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <textarea name="description" class="form-control mb-2"
        placeholder="Deskripsi"><?= htmlspecialchars($book['description']) ?></textarea>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="books.php" class="btn btn-secondary">Batal</a>
</form>

<?php include '../views/footer.php'; ?>