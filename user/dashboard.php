<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$userId   = $_SESSION['user_id'];
$username = $_SESSION['username'];

$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM book_requests WHERE user_id = ?");
$totalStmt->execute([$userId]);
$totalRequests = $totalStmt->fetchColumn();

$pendingStmt = $pdo->prepare("SELECT COUNT(*) FROM book_requests WHERE user_id = ? AND status = 'pending'");
$pendingStmt->execute([$userId]);
$pending = $pendingStmt->fetchColumn();

$completedStmt = $pdo->prepare("SELECT COUNT(*) FROM book_requests WHERE user_id = ? AND status = 'completed'");
$completedStmt->execute([$userId]);
$completed = $completedStmt->fetchColumn();

$listStmt = $pdo->prepare("SELECT * FROM book_requests WHERE user_id = ? ORDER BY id DESC");
$listStmt->execute([$userId]);
$requests = $listStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    background:linear-gradient(135deg,#667eea,#764ba2);
    min-height:100vh;
}

.navbar{
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(10px);
    padding:18px 35px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    color:#fff;
}

.navbar h2{
    font-size:24px;
}

.nav-links a{
    text-decoration:none;
    color:#fff;
    margin-left:15px;
    font-weight:bold;
}

.nav-links a:hover{
    text-decoration:underline;
}

.wrapper{
    width:92%;
    max-width:1200px;
    margin:30px auto;
}

.welcome-box{
    background:rgba(255,255,255,0.95);
    padding:25px;
    border-radius:18px;
    box-shadow:0 15px 35px rgba(0,0,0,0.15);
    margin-bottom:25px;
}

.welcome-box h1{
    color:#222;
    margin-bottom:8px;
}

.welcome-box p{
    color:#666;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:25px;
}

.card{
    background:white;
    border-radius:18px;
    padding:22px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.card h3{
    color:#666;
    font-size:15px;
    margin-bottom:8px;
}

.card h2{
    color:#333;
    font-size:28px;
}

.section{
    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.top-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:18px;
    gap:15px;
    flex-wrap:wrap;
}

.btn{
    padding:12px 18px;
    border:none;
    border-radius:10px;
    text-decoration:none;
    color:white;
    font-weight:bold;
    cursor:pointer;
    display:inline-block;
}

.btn-primary{
    background:#667eea;
}

.btn-primary:hover{
    background:#5563d8;
}

.btn-danger{
    background:#e74c3c;
}

.btn-danger:hover{
    background:#c0392b;
}

.badge{
    padding:6px 10px;
    border-radius:20px;
    font-size:13px;
    color:#fff;
    font-weight:bold;
}

.pending{
    background:#f39c12;
}

.progress{
    background:#3498db;
}

.completed{
    background:#27ae60;
}

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    padding:14px;
    text-align:left;
    border-bottom:1px solid #eee;
}

th{
    background:#667eea;
    color:white;
}

.empty{
    text-align:center;
    padding:25px;
    color:#666;
}

@media(max-width:768px){
    .navbar{
        flex-direction:column;
        gap:10px;
    }

    .top-row{
        flex-direction:column;
        align-items:stretch;
    }

    table{
        font-size:14px;
    }
}
</style>
</head>
<body>

<div class="navbar">
    <h2>Book Request System</h2>

    <div class="nav-links">
        <a href="request_book.php">Request Book</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="wrapper">

    <div class="welcome-box">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?> 👋</h1>
        <p>Manage your book requests from your dashboard.</p>
    </div>

    <div class="grid">

        <div class="card">
            <h3>Total Requests</h3>
            <h2><?php echo $totalRequests; ?></h2>
        </div>

        <div class="card">
            <h3>Pending</h3>
            <h2><?php echo $pending; ?></h2>
        </div>

        <div class="card">
            <h3>Completed</h3>
            <h2><?php echo $completed; ?></h2>
        </div>

    </div>

    <div class="section">

        <div class="top-row">
            <h2>My Requests</h2>
            <a href="request_book.php" class="btn btn-primary">+ New Request</a>
        </div>

        <?php if(count($requests) > 0){ ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Book Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php foreach($requests as $row){ ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td>
                    <?php if($row['status'] == 'pending'){ ?>
                        <span class="badge pending">Pending</span>
                    <?php } elseif($row['status'] == 'in_progress'){ ?>
                        <span class="badge progress">In Progress</span>
                    <?php } else { ?>
                        <span class="badge completed">Completed</span>
                    <?php } ?>
                </td>

                <td>
                    <?php if($row['status'] == 'pending'){ ?>
                        <a class="btn btn-danger"
                           href="cancel_request.php?id=<?php echo $row['id']; ?>"
                           onclick="return confirm('Cancel this request?')">
                           Cancel
                        </a>
                    <?php } else { ?>
                        --
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>

        </table>

        <?php } else { ?>

            <div class="empty">
                No requests found. Click "New Request" to add one.
            </div>

        <?php } ?>

    </div>

</div>

</body>
</html>