<?php
require '../config/db.php';

$username = "admin";
$newPassword = "admin123";
$hash = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
$stmt->execute([$hash, $username]);

echo "Admin password reset successfully.";
?>