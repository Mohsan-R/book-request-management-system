<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['superadmin_id']) || $_SESSION['role'] != 'superadmin') {
    header("Location: login.php");
    exit();
}

$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalAdmins = $pdo->query("SELECT COUNT(*) FROM admins WHERE role='admin'")->fetchColumn();
$totalRequests = $pdo->query("SELECT COUNT(*) FROM book_requests")->fetchColumn();
$completed = $pdo->query("SELECT COUNT(*) FROM book_requests WHERE status='completed'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Super Admin Dashboard</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;}

body{
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#667eea,#764ba2);
    min-height:100vh;
}

.navbar{
    background:rgba(255,255,255,0.15);
    padding:18px 35px;
    display:flex;
    justify-content:space-between;
    color:#fff;
}

.navbar a{
    color:#fff;
    text-decoration:none;
    margin-left:15px;
    font-weight:bold;
}

.wrapper{
    width:92%;
    max-width:1200px;
    margin:35px auto;
}

.box{
    background:white;
    padding:25px;
    border-radius:18px;
    margin-bottom:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.card{
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.card h3{color:#666;margin-bottom:10px;}
.card h2{font-size:30px;color:#222;}

.menu a{
    display:inline-block;
    margin:10px 10px 0 0;
    padding:12px 18px;
    background:#667eea;
    color:white;
    text-decoration:none;
    border-radius:10px;
}
</style>
</head>
<body>

<div class="navbar">
    <h2>Super Admin Panel</h2>
    <div>
        <a href="manage_requests.php">Requests</a>
        <a href="manage_users.php">Users</a>
        <a href="manage_admins.php">Admins</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="wrapper">

    <div class="box">
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        <p>Full system management dashboard</p>

        <div class="menu">
            <a href="manage_requests.php">Manage Requests</a>
            <a href="manage_users.php">Manage Users</a>
            <a href="manage_admins.php">Manage Admins</a>
        </div>
    </div>

    <div class="grid">

        <div class="card">
            <h3>Total Users</h3>
            <h2><?php echo $totalUsers; ?></h2>
        </div>

        <div class="card">
            <h3>Total Admins</h3>
            <h2><?php echo $totalAdmins; ?></h2>
        </div>

        <div class="card">
            <h3>Total Requests</h3>
            <h2><?php echo $totalRequests; ?></h2>
        </div>

        <div class="card">
            <h3>Completed</h3>
            <h2><?php echo $completed; ?></h2>
        </div>

    </div>

</div>

</body>
</html>