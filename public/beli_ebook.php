<?php
include '../config/db.php';
session_start();

// Cek apakah pengguna sudah login dan memiliki role 'user'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

// Cek apakah parameter id ada di URL
if (!isset($_GET['id'])) {
    header("Location: user_books.php");
    exit;
}

$id_ebook = $_GET['id'];

// Ambil data buku dari database berdasarkan id_ebook
$stmt = $pdo->prepare("SELECT * FROM books WHERE id_book = ?");
$stmt->execute([$id_ebook]);
$book = $stmt->fetch();

// Jika buku tidak ditemukan
if (!$book) {
    echo "Buku tidak ditemukan!";
    exit;
}

// Proses pembelian ebook
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data yang dikirimkan dari form
    $harga = $book['harga'];  // Gunakan harga dari database
    $metode = $_POST['metode'];

    // Masukkan data transaksi ke dalam tabel transaksi_ebook
    $stmt = $pdo->prepare("INSERT INTO transaksi_ebook (user_id, ebook_id, harga, metode_pembayaran) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $id_ebook, $harga, $metode]);

    // Arahkan pengguna ke halaman riwayat transaksi
    header("Location: riwayat_transaksi.php");
    exit;
}

include '../views/header.php';
?>

<h2>Beli Buku: <?= htmlspecialchars($book['title']) ?></h2>
<form method="post">
    <div class="mb-3">
        <label>Harga (Rp)</label>
        <input type="number" name="harga" value="<?= number_format($book['harga'], 0, ',', '.') ?>" class="form-control"
            readonly required>
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