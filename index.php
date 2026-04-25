<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BookHive | Portal</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    background:#f5f3ee;
    color:#111;
}

.wrapper{
    width:92%;
    max-width:1300px;
    margin:0 auto;
    padding:30px 0 60px;
}

.hero{
    text-align:center;
    margin-bottom:45px;
}

.hero h1{
    font-size:72px;
    font-weight:800;
    margin-bottom:18px;
    letter-spacing:-2px;
}

.hero p{
    max-width:900px;
    margin:0 auto;
    font-size:22px;
    line-height:1.8;
    color:#5a6470;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:35px;
}

.card{
    background:white;
    border:1px solid #e8e1d8;
    border-radius:18px;
    padding:35px;
    box-shadow:0 8px 18px rgba(0,0,0,0.05);
    transition:0.3s ease;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 18px 30px rgba(0,0,0,0.08);
}

.icon{
    width:74px;
    height:74px;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:34px;
    margin:0 auto 18px;
}

.green{ background:#e6ece7; color:#2f5d46; }
.gray{ background:#ece9e2; color:#222; }
.red{ background:#f6e6e6; color:#b71c1c; }

.card h2{
    text-align:center;
    font-size:24px;
    margin-bottom:10px;
}

.card p{
    text-align:center;
    color:#5d6670;
    font-size:16px;
    margin-bottom:28px;
}

.btn{
    display:block;
    width:100%;
    text-align:center;
    padding:15px;
    border-radius:8px;
    text-decoration:none;
    font-size:17px;
    font-weight:700;
    margin-bottom:14px;
    transition:0.3s;
}

.btn-user{
    background:#2f5d46;
    color:white;
}

.btn-user:hover{
    background:#244635;
}

.btn-light{
    background:#f7f7f7;
    color:#111;
    border:1px solid #ddd;
}

.btn-light:hover{
    background:#ececec;
}

.btn-admin{
    background:#ece9e2;
    color:#111;
}

.btn-admin:hover{
    background:#ddd8cf;
}

.btn-super{
    background:#c01919;
    color:white;
}

.btn-super:hover{
    background:#a31212;
}

.footer{
    text-align:center;
    margin-top:45px;
    color:#777;
    font-size:14px;
}

@media(max-width:768px){

.hero h1{
    font-size:44px;
}

.hero p{
    font-size:18px;
}

}
</style>
</head>
<body>

<div class="wrapper">

    <div class="hero">
        <h1>Welcome to BookHive</h1>

        <p>
            Your centralized platform for discovering, requesting,
            and managing professional development books.
            Choose your portal below to get started.
        </p>
    </div>

    <div class="grid">

        <!-- User -->
        <div class="card">

            <div class="icon green">👤</div>

            <h2>Learners</h2>
            <p>Request and track your books</p>

            <a href="user/login.php" class="btn btn-user">User Login</a>
            <a href="user/register.php" class="btn btn-light">Register</a>

        </div>

        <!-- Admin -->
        <div class="card">

            <div class="icon gray">🛡️</div>

            <h2>Administrators</h2>
            <p>Manage user requests and stats</p>

            <a href="admin/login.php" class="btn btn-admin">Admin Login</a>

        </div>

        <!-- Super Admin -->
        <div class="card">

            <div class="icon red">🛡</div>

            <h2>System Ops</h2>
            <p>Manage users and admins</p>

            <a href="superadmin/login.php" class="btn btn-super">
                Super Admin Login
            </a>

        </div>

    </div>

    <div class="footer">
        Book Request Management System
    </div>

</div>

</body>
</html>