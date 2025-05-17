<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

include '../views/header.php';
?>

<div class="text-center">
    <h2 class="mb-3 text-primary">Halo, Selamat Datang di Perpustakaan Digital</h2>
    <p class="lead">Silakan jelajahi koleksi buku dan transaksi Anda di bawah ini.</p>
</div>

<div class="row justify-content-center mt-4">
    <div class="col-md-3 mb-3">
        <a href="daftar_buku.php" class="btn btn-outline-primary w-100 py-3">
            📖 Lihat Koleksi Buku
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="user_books.php" class="btn btn-outline-secondary w-100 py-3">
            📚 Buku Digital
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="riwayat_transaksi.php" class="btn btn-outline-info w-100 py-3">
            🧾 Riwayat Pembelian eBook
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="logout.php" class="btn btn-outline-danger w-100 py-3">
            🚪 Logout
        </a>
    </div>
</div>

<?php include '../views/footer.php'; ?>