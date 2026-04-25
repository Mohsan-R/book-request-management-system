<?php
require '../config/db.php';
session_start();

$message = "";
$type = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == "" || $password == "") {

        $message = "All fields are required.";
        $type = "error";

    } else {

        $stmt = $pdo->prepare("
            SELECT * FROM admins
            WHERE username = ? AND role = 'superadmin'
        ");
        $stmt->execute([$username]);

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {

            $_SESSION['superadmin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = "superadmin";

            header("Location: dashboard.php");
            exit();

        } else {

            $message = "Invalid username or password.";
            $type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Super Admin Login</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;}

body{
    font-family:Arial,sans-serif;
    min-height:100vh;
    background:linear-gradient(135deg,#667eea,#764ba2);
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.card{
    width:100%;
    max-width:430px;
    background:rgba(255,255,255,0.95);
    padding:35px;
    border-radius:22px;
    box-shadow:0 20px 45px rgba(0,0,0,0.18);
}

.logo{
    width:75px;height:75px;
    margin:0 auto 15px;
    border-radius:50%;
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:28px;
    font-weight:bold;
}

h2{text-align:center;color:#222;margin-bottom:8px;}
.sub{text-align:center;color:#666;margin-bottom:20px;font-size:14px;}

.message{
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
    text-align:center;
}

.error{background:#ffe8e8;color:#d8000c;}

label{
    display:block;
    margin:12px 0 6px;
    font-weight:bold;
    color:#444;
}

input{
    width:100%;
    padding:13px;
    border:1px solid #ccc;
    border-radius:10px;
    outline:none;
}

input:focus{border-color:#667eea;}

button{
    width:100%;
    padding:14px;
    margin-top:20px;
    border:none;
    border-radius:10px;
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:#fff;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
}
</style>
</head>
<body>

<div class="card">

    <div class="logo">S</div>

    <h2>Super Admin Login</h2>
    <p class="sub">Full system control panel</p>

    <?php if($message != ""){ ?>
        <div class="message <?php echo $type; ?>">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">Login</button>

    </form>

</div>

</body>
</html>