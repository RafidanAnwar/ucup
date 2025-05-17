<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
$userRole = $_SESSION['role'] ?? null;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Perpustakaan Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                📚 Perpustakaan
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if ($userRole === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/ebook/public/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/ebook/public/daftar_buku.php">Buku</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/ebook/public/genres.php">Genre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/ebook/public/admin_transaksi.php">Transaksi</a>
                        </li>
                    <?php elseif ($userRole === 'user'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/ebook/public/user_dashboard.php">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/ebook/public/daftar_buku.php">Buku</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/ebook/public/riwayat_transaksi.php">Riwayat</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/ebook/public/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/ebook/public/login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">