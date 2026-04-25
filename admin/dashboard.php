<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$totalUsers = $pdo->query("
    SELECT COUNT(DISTINCT user_id)
    FROM book_requests
")->fetchColumn();

$totalRequests = $pdo->query("
    SELECT COUNT(*) FROM book_requests
")->fetchColumn();

$inProgress = $pdo->query("
    SELECT COUNT(*) FROM book_requests
    WHERE status='in_progress'
")->fetchColumn();

$completed = $pdo->query("
    SELECT COUNT(*) FROM book_requests
    WHERE status='completed'
")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

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
    align-items:center;
    color:#fff;
}

.navbar a{
    color:#fff;
    text-decoration:none;
    font-weight:bold;
}

.wrapper{
    width:92%;
    max-width:1100px;
    margin:35px auto;
}

.title-box{
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
    margin-bottom:25px;
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

.card h3{
    color:#666;
    margin-bottom:10px;
}

.card h2{
    color:#222;
    font-size:30px;
}
</style>
</head>
<body>

<div class="navbar">
    <h2>Admin Panel</h2>
    <a href="../logout.php">Logout</a>
</div>

<div class="wrapper">

    <div class="title-box">
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        <p>System statistics overview</p>
    </div>

    <div class="grid">

        <div class="card">
            <h3>Total Unique Users</h3>
            <h2><?php echo $totalUsers; ?></h2>
        </div>

        <div class="card">
            <h3>Total Requests</h3>
            <h2><?php echo $totalRequests; ?></h2>
        </div>

        <div class="card">
            <h3>In Progress</h3>
            <h2><?php echo $inProgress; ?></h2>
        </div>

        <div class="card">
            <h3>Completed</h3>
            <h2><?php echo $completed; ?></h2>
        </div>

    </div>

</div>

</body>
</html>