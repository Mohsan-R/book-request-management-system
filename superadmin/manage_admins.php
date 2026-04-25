<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['superadmin_id']) || $_SESSION['role'] != 'superadmin') {
    header("Location: login.php");
    exit();
}

$message = "";
$type = "";

/* Add New Admin */
if (isset($_POST['add_admin'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username != "" && $password != "") {

        $check = $pdo->prepare("
            SELECT id FROM admins
            WHERE username = ?
        ");
        $check->execute([$username]);

        if ($check->rowCount() > 0) {

            $message = "Username already exists.";
            $type = "error";

        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO admins(username,password,role)
                VALUES(?,?,'admin')
            ");
            $stmt->execute([$username, $hash]);

            $message = "New admin added successfully.";
            $type = "success";
        }

    } else {

        $message = "All fields are required.";
        $type = "error";
    }
}

/* Delete Admin */
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    $check = $pdo->prepare("
        SELECT role FROM admins
        WHERE id = ?
    ");
    $check->execute([$id]);

    $row = $check->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['role'] == 'admin') {

        $stmt = $pdo->prepare("
            DELETE FROM admins
            WHERE id = ?
        ");
        $stmt->execute([$id]);

        $message = "Admin deleted successfully.";
        $type = "success";

    } else {

        $message = "Super Admin cannot be deleted.";
        $type = "error";
    }
}

/* Fetch Admins */
$stmt = $pdo->query("
    SELECT * FROM admins
    ORDER BY id DESC
");

$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Admins</title>

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
    max-width:1200px;
    margin:30px auto;
}

.box{
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
    margin-bottom:25px;
}

h1{
    margin-bottom:18px;
    color:#222;
}

.message{
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
}

.success{
    background:#e8fff0;
    color:#0a7a35;
}

.error{
    background:#ffe8e8;
    color:#d8000c;
}

input{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
    margin:8px 0;
}

button,.btn{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    color:white;
    cursor:pointer;
    text-decoration:none;
    font-weight:bold;
}

.btn-add{
    background:#27ae60;
}

.btn-delete{
    background:#e74c3c;
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

.role{
    padding:6px 10px;
    border-radius:20px;
    color:white;
    font-size:13px;
    font-weight:bold;
}

.admin{background:#3498db;}
.super{background:#8e44ad;}
</style>
</head>
<body>

<div class="navbar">
    <h2>Super Admin Panel</h2>

    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_requests.php">Requests</a>
        <a href="manage_users.php">Users</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="wrapper">

    <div class="box">

        <h1>Add New Admin</h1>

        <?php if($message != ""){ ?>
            <div class="message <?php echo $type; ?>">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <input type="text" name="username" placeholder="Enter username" required>

            <input type="password" name="password" placeholder="Enter password" required>

            <button class="btn-add" name="add_admin">
                Add Admin
            </button>

        </form>

    </div>

    <div class="box">

        <h1>All Admins</h1>

        <?php if(count($admins) > 0){ ?>

        <table>

            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Delete</th>
            </tr>

            <?php foreach($admins as $row){ ?>

            <tr>
                <td><?php echo $row['id']; ?></td>

                <td><?php echo htmlspecialchars($row['username']); ?></td>

                <td>
                    <?php if($row['role'] == 'admin'){ ?>
                        <span class="role admin">Admin</span>
                    <?php } else { ?>
                        <span class="role super">Super Admin</span>
                    <?php } ?>
                </td>

                <td>
                    <?php if($row['role'] == 'admin'){ ?>

                        <a class="btn btn-delete"
                           href="?delete=<?php echo $row['id']; ?>"
                           onclick="return confirm('Delete this admin?')">
                           Delete
                        </a>

                    <?php } else { ?>
                        Protected
                    <?php } ?>
                </td>
            </tr>

            <?php } ?>

        </table>

        <?php } else { ?>

            <p>No admins found.</p>

        <?php } ?>

    </div>

</div>

</body>
</html>