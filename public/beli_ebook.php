<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: user_books.php");
    exit;
}

$id_ebook = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM books WHERE id_book = ?");
$stmt->execute([$id_ebook]);
$book = $stmt->fetch();

if (!$book) {
    echo "Buku tidak ditemukan!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $harga = $_POST['harga'];
    $metode = $_POST['metode'];

    $stmt = $pdo->prepare("INSERT INTO transaksi_ebook (user_id, ebook_id, harga, metode_pembayaran) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $id_ebook, $harga, $metode]);

    header("Location: riwayat_transaksi.php");
    exit;
}

include '../views/header.php';
?>

<h2>Beli Buku: <?= htmlspecialchars($book['title']) ?></h2>
<form method="post">
    <div class="mb-3">
        <label>Harga (Rp)</label>
        <input type="number" name="harga" value="15000" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Metode Pembayaran</label>
        <select name="metode" class="form-control" required>
            <option value="transfer">Transfer</option>
            <option value="e-wallet">E-Wallet</option>
            <option value="kartu kredit">Kartu Kredit</option>
            <option value="lainnya">Lainnya</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Beli Sekarang</button>
    <a href="user_books.php" class="btn btn-secondary">Kembali</a>
</form>

<?php include '../views/footer.php'; ?>