<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include '../views/header.php';
?>

<div class="text-center">
    <h2 class="text-primary mb-3">Dashboard Admin</h2>
    <p class="lead">Kelola koleksi buku, genre, dan transaksi eBook dengan mudah.</p>
</div>

<div class="row justify-content-center mt-4">
    <div class="col-md-3 mb-3">
        <a href="daftar_buku.php" class="btn btn-outline-primary w-100 py-3">
            📚 Lihat Semua Buku
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="admin_users.php" class="btn btn-outline-danger w-100 py-3">
            👥 Kelola User
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="books.php" class="btn btn-outline-success w-100 py-3">
            ✏️ Kelola Buku
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="genres.php" class="btn btn-outline-secondary w-100 py-3">
            🏷️ Kelola Genre
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="admin_transaksi.php" class="btn btn-outline-info w-100 py-3">
            💳 Semua Transaksi
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="logout.php" class="btn btn-outline-danger w-100 py-3">
            🚪 Logout
        </a>
    </div>
</div>

<?php include '../views/footer.php'; ?>