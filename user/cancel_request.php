<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("
        DELETE FROM book_requests
        WHERE id = ?
        AND user_id = ?
        AND status = 'pending'
    ");

    $stmt->execute([$id, $user_id]);
}

header("Location: dashboard.php");
exit();