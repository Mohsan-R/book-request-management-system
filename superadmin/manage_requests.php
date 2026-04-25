<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['superadmin_id']) || $_SESSION['role'] != 'superadmin') {
    header("Location: login.php");
    exit();
}

/* Update Status */
if (isset($_POST['update_status'])) {

    $id = (int) $_POST['request_id'];
    $status = $_POST['status'];

    $allowed = ['pending', 'in_progress', 'completed'];

    if (in_array($status, $allowed)) {

        $stmt = $pdo->prepare("
            UPDATE book_requests
            SET status = ?
            WHERE id = ?
        ");
        $stmt->execute([$status, $id]);
    }
}

/* Delete Request */
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    $stmt = $pdo->prepare("
        DELETE FROM book_requests
        WHERE id = ?
    ");
    $stmt->execute([$id]);
}

/* Fetch All Requests */
$stmt = $pdo->query("
    SELECT book_requests.*, users.username
    FROM book_requests
    INNER JOIN users ON users.id = book_requests.user_id
    ORDER BY book_requests.id DESC
");

$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Requests</title>

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
    margin-left:15px;
    font-weight:bold;
}

.wrapper{
    width:95%;
    max-width:1300px;
    margin:30px auto;
}

.box{
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

h1{
    margin-bottom:20px;
    color:#222;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:left;
}

th{
    background:#667eea;
    color:white;
}

select{
    padding:8px;
    border-radius:8px;
    border:1px solid #ccc;
}

button,.btn{
    padding:8px 12px;
    border:none;
    border-radius:8px;
    color:white;
    text-decoration:none;
    cursor:pointer;
    font-weight:bold;
}

.btn-update{
    background:#27ae60;
}

.btn-delete{
    background:#e74c3c;
}

.badge{
    padding:6px 10px;
    border-radius:20px;
    color:white;
    font-size:13px;
    font-weight:bold;
}

.pending{background:#f39c12;}
.progress{background:#3498db;}
.completed{background:#27ae60;}

@media(max-width:900px){
    table{
        font-size:13px;
    }
}
</style>
</head>
<body>

<div class="navbar">
    <h2>Super Admin Panel</h2>

    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_users.php">Users</a>
        <a href="manage_admins.php">Admins</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="wrapper">

    <div class="box">

        <h1>Manage Book Requests</h1>

        <?php if(count($requests) > 0){ ?>

        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Book Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Change Status</th>
                <th>Delete</th>
            </tr>

            <?php foreach($requests as $row){ ?>

            <tr>
                <td><?php echo $row['id']; ?></td>

                <td><?php echo htmlspecialchars($row['username']); ?></td>

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
                    <form method="POST" style="display:flex;gap:8px;align-items:center;">

                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">

                        <select name="status">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>

                        <button class="btn-update" name="update_status">
                            Update
                        </button>

                    </form>
                </td>

                <td>
                    <a class="btn btn-delete"
                       href="?delete=<?php echo $row['id']; ?>"
                       onclick="return confirm('Delete this request?')">
                       Delete
                    </a>
                </td>
            </tr>

            <?php } ?>

        </table>

        <?php } else { ?>

            <p>No requests found.</p>

        <?php } ?>

    </div>

</div>

</body>
</html>