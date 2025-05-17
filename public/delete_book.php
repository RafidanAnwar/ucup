<?php
include '../config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM books WHERE id_book = ?");
    $stmt->execute([$id]);
}

header("Location: books.php");
exit;
