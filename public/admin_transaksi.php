<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include '../config/db.php';
include '../views/header.php';

// Ambil semua transaksi dengan JOIN
$sql = "
    SELECT t.*, b.title, u.username 
    FROM transaksi_ebook t
    JOIN books b ON t.ebook_id = b.id_book
    JOIN users u ON t.user_id = u.id
    ORDER BY t.tanggal_pembelian DESC
";
$transaksi = $pdo->query($sql)->fetchAll();
?>

<h2>Daftar Semua Transaksi eBook</h2>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Judul Buku</th>
            <th>Pembeli</th>
            <th>Tanggal</th>
            <th>Harga</th>
            <th>Metode Pembayaran</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transaksi as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['title']) ?></td>
                <td><?= htmlspecialchars($t['username']) ?></td>
                <td><?= $t['tanggal_pembelian'] ?></td>
                <td>Rp <?= number_format($t['harga'], 0, ',', '.') ?></td>
                <td><?= ucfirst($t['metode_pembayaran']) ?></td>
                <td><?= ucfirst($t['status']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../views/footer.php'; ?>