<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['superadmin_id']) || $_SESSION['role'] != 'superadmin') {
    header("Location: login.php");
    exit();
}

$message = "";
$type = "";

/* Reset User Password */
if (isset($_POST['reset_password'])) {

    $id = (int) $_POST['user_id'];
    $newPassword = "user123";
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        UPDATE users
        SET password = ?
        WHERE id = ?
    ");
    $stmt->execute([$hash, $id]);

    $message = "User password reset to: user123";
    $type = "success";
}

/* Delete User */
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    $stmt = $pdo->prepare("
        DELETE FROM users
        WHERE id = ?
    ");
    $stmt->execute([$id]);

    $message = "User deleted successfully.";
    $type = "success";
}

/* Fetch Users */
$stmt = $pdo->query("
    SELECT * FROM users
    ORDER BY id DESC
");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users</title>

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

button,.btn{
    padding:8px 12px;
    border:none;
    border-radius:8px;
    color:white;
    cursor:pointer;
    text-decoration:none;
    font-weight:bold;
}

.btn-reset{
    background:#27ae60;
}

.btn-delete{
    background:#e74c3c;
}

@media(max-width:900px){
    table{font-size:13px;}
}
</style>
</head>
<body>

<div class="navbar">
    <h2>Super Admin Panel</h2>

    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_requests.php">Requests</a>
        <a href="manage_admins.php">Admins</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="wrapper">

    <div class="box">

        <h1>Manage Users</h1>

        <?php if($message != ""){ ?>
            <div class="message <?php echo $type; ?>">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <?php if(count($users) > 0){ ?>

        <table>

            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Reset Password</th>
                <th>Delete</th>
            </tr>

            <?php foreach($users as $row){ ?>

            <tr>
                <td><?php echo $row['id']; ?></td>

                <td><?php echo htmlspecialchars($row['username']); ?></td>

                <td><?php echo htmlspecialchars($row['email']); ?></td>

                <td>
                    <form method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <button class="btn-reset" name="reset_password">
                            Reset
                        </button>
                    </form>
                </td>

                <td>
                    <a class="btn btn-delete"
                       href="?delete=<?php echo $row['id']; ?>"
                       onclick="return confirm('Delete this user?')">
                       Delete
                    </a>
                </td>
            </tr>

            <?php } ?>

        </table>

        <?php } else { ?>

            <p>No users found.</p>

        <?php } ?>

    </div>

</div>

</body>
</html>