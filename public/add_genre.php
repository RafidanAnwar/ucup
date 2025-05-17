<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO genres (name) VALUES (?)");
        $stmt->execute([$name]);
        header('Location: genres.php');
        exit;
    }
}

include '../views/header.php';
?>

<h2>Tambah Genre Baru</h2>
<form method="post">
    <div class="mb-3">
        <label>Nama Genre</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="genres.php" class="btn btn-secondary">Batal</a>
</form>

<?php include '../views/footer.php'; ?>