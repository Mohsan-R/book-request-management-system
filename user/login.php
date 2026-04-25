<?php
require '../config/db.php';
session_start();

$message = "";
$type = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email == "" || $password == "") {

        $message = "All fields are required.";
        $type = "error";

    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = "user";

            header("Location: dashboard.php");
            exit();

        } else {

            $message = "Invalid email or password.";
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
<title>Login | Book Request System</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    min-height:100vh;
    background:linear-gradient(135deg,#667eea,#764ba2);
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.container{
    width:100%;
    max-width:430px;
}

.card{
    background:rgba(255,255,255,0.95);
    border-radius:22px;
    padding:35px;
    box-shadow:0 20px 45px rgba(0,0,0,0.18);
    animation:fadeIn 0.6s ease;
}

.logo{
    width:70px;
    height:70px;
    margin:0 auto 15px;
    border-radius:50%;
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
    font-weight:bold;
}

h2{
    text-align:center;
    color:#222;
    margin-bottom:8px;
}

.sub{
    text-align:center;
    color:#666;
    font-size:14px;
    margin-bottom:22px;
}

.message{
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
    font-size:14px;
    text-align:center;
}

.success{
    background:#e8fff0;
    color:#0a7a35;
}

.error{
    background:#ffe8e8;
    color:#d8000c;
}

label{
    display:block;
    margin-top:12px;
    margin-bottom:6px;
    color:#444;
    font-size:14px;
    font-weight:bold;
}

input{
    width:100%;
    padding:13px 14px;
    border:1px solid #d9d9d9;
    border-radius:10px;
    font-size:15px;
    transition:0.3s;
    outline:none;
}

input:focus{
    border-color:#667eea;
    box-shadow:0 0 0 4px rgba(102,126,234,0.12);
}

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
    transition:0.3s;
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(102,126,234,0.25);
}

.bottom{
    text-align:center;
    margin-top:18px;
    color:#555;
    font-size:14px;
}

.bottom a{
    color:#667eea;
    text-decoration:none;
    font-weight:bold;
}

.bottom a:hover{
    text-decoration:underline;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(15px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>
</head>
<body>

<div class="container">

    <div class="card">

        <div class="logo">B</div>

        <h2>Welcome Back</h2>
        <p class="sub">Login to continue</p>

        <?php if($message != "") { ?>
            <div class="message <?php echo $type; ?>">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <button type="submit" name="login">Login</button>

        </form>

        <div class="bottom">
            Don't have an account?
            <a href="register.php">Register</a>
        </div>

    </div>

</div>

</body>
</html>