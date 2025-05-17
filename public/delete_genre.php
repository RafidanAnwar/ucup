<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include '../config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM genres WHERE id_genre = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        echo "<script>alert('Gagal menghapus genre: Genre masih digunakan oleh buku'); window.location='genres.php';</script>";
        exit;
    }

    header('Location: genres.php');
    exit;
}
