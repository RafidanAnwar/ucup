<?php
// FILE: delete_user.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include '../config/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

header('Location: admin_users.php');
exit;
?>