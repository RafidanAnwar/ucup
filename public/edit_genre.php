<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include '../config/db.php';

if (!isset($_GET['id'])) {
    header('Location: genres.php');
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM genres WHERE id_genre = ?");
$stmt->execute([$id]);
$genre = $stmt->fetch();

if (!$genre) {
    echo "Genre tidak ditemukan!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $update = $pdo->prepare("UPDATE genres SET name = ? WHERE id_genre = ?");
        $update->execute([$name, $id]);
        header('Location: genres.php');
        exit;
    }
}

include '../views/header.php';
?>

<h2>Edit Genre</h2>
<form method="post">
    <div class="mb-3">
        <label>Nama Genre</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($genre['name']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="genres.php" class="btn btn-secondary">Batal</a>
</form>

<?php include '../views/footer.php'; ?>