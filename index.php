<?php
session_start();
include 'config/db.php';

// Ambil daftar genre untuk dropdown
$genres = $pdo->query("SELECT * FROM genres ORDER BY name")->fetchAll();

// Ambil filter dari form
$keyword = $_GET['keyword'] ?? '';
$genre_id = $_GET['genre'] ?? '';

// Bangun query dinamis
$sql = "
    SELECT books.*, genres.name AS genre 
    FROM books 
    LEFT JOIN genres ON books.id_genre = genres.id_genre 
    WHERE 1
";
$params = [];

if ($keyword !== '') {
    $sql .= " AND (books.title LIKE ? OR books.author LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}

if ($genre_id !== '') {
    $sql .= " AND books.id_genre = ?";
    $params[] = $genre_id;
}

$sql .= " ORDER BY books.id_book DESC LIMIT 12";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Perpustakaan Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            background: linear-gradient(135deg, #e0f7fa, #ffffff);
            font-family: 'Segoe UI', sans-serif;
        }

        .hero {
            padding: 100px 20px;
            text-align: center;
            background: #0288d1;
            color: white;
        }

        .book-card {
            height: 100%;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            transition: 0.3s;
            background: white;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <!-- Hero Section -->
    <section class="hero">
        <div data-aos="fade-down">
            <h1 class="display-4 mb-3">📚 Selamat Datang di Perpustakaan Digital</h1>
            <p class="lead">Akses buku dari mana saja dan kapan saja.</p>
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="public/login.php" class="btn btn-light">Login</a>
                <a href="public/register.php" class="btn btn-outline-light">Daftar</a>
            </div>
        </div>
    </section>

    <!-- Form Filter dan Pencarian -->
    <section class="container mt-4">
        <form method="GET" action="#buku" class="row g-2 align-items-end" data-aos="fade-down">
            <div class="col-md-6">
                <label for="keyword" class="form-label">Cari Judul / Penulis</label>
                <input type="text" name="keyword" id="keyword" class="form-control"
                    value="<?= htmlspecialchars($keyword) ?>" placeholder="Contoh: Dilan atau Tere Liye">
            </div>
            <div class="col-md-4">
                <label for="genre" class="form-label">Filter Genre</label>
                <select name="genre" id="genre" class="form-select">
                    <option value="">Semua Genre</option>
                    <?php foreach ($genres as $g): ?>
                        <option value="<?= $g['id_genre'] ?>" <?= $genre_id == $g['id_genre'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
    </section>

    <!-- Daftar Buku Preview -->
    <section class="container mt-5" id="buku">
        <h2 class="text-center mb-4" data-aos="fade-up">
            <?= $keyword || $genre_id ? 'Hasil Pencarian Buku' : 'Koleksi Buku Terbaru' ?>
        </h2>

        <?php if (count($books) === 0): ?>
            <p class="text-center text-muted">Tidak ada buku yang ditemukan.</p>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($books as $book): ?>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $book['id_book'] * 50 ?>">
                        <div class="book-card">
                            <h5><?= htmlspecialchars($book['title']) ?></h5>
                            <p><strong>Penulis:</strong> <?= htmlspecialchars($book['author']) ?></p>
                            <p><strong>Genre:</strong> <?= htmlspecialchars($book['genre']) ?></p>
                            <p><strong>Tahun:</strong> <?= $book['year'] ?></p>
                            <p><strong>Harga:</strong> Rp <?= number_format($book['harga'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="public/daftar_buku.php" class="btn btn-outline-primary">Lihat Semua Buku</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center text-muted mt-5 py-4 border-top">
        <small>&copy; <?= date('Y') ?> Perpustakaan Digital. All rights reserved.</small>
    </footer>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>