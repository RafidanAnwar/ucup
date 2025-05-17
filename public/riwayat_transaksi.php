<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

include '../config/db.php';
include '../views/header.php';

$stmt = $pdo->prepare("
    SELECT t.*, b.title 
    FROM transaksi_ebook t 
    JOIN books b ON t.ebook_id = b.id_book 
    WHERE t.user_id = ? 
    ORDER BY t.tanggal_pembelian DESC
");
$stmt->execute([$_SESSION['user_id']]);
$transaksi = $stmt->fetchAll();
?>

<h2>Riwayat Pembelian eBook</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Judul Buku</th>
            <th>Tanggal</th>
            <th>Harga</th>
            <th>Metode</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transaksi as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['title']) ?></td>
                <td><?= $t['tanggal_pembelian'] ?></td>
                <td>Rp <?= number_format($t['harga'], 0, ',', '.') ?></td>
                <td><?= $t['metode_pembayaran'] ?></td>
                <td><?= ucfirst($t['status']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../views/footer.php'; ?>