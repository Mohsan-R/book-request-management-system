<?php
require '../config/db.php';

$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$role = "admin";

$stmt = $pdo->prepare("INSERT INTO admins(username,password,role) VALUES(?,?,?)");
$stmt->execute([$username,$password,$role]);

echo "Admin Created Successfully";
?>