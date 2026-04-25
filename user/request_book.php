<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$message = "";
$type = "";

if (isset($_POST['submit_request'])) {

    $category   = trim($_POST['category']);
    $book_title = trim($_POST['book_title']);
    $user_id    = $_SESSION['user_id'];

    if ($category == "" || $book_title == "") {
        $message = "Please select category and book.";
        $type = "error";

    } else {

        $stmt = $pdo->prepare("
            INSERT INTO book_requests (user_id, book_title, category, status)
            VALUES (?, ?, ?, 'pending')
        ");

        $stmt->execute([$user_id, $book_title, $category]);

        $message = "Book request submitted successfully.";
        $type = "success";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Request Book</title>

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
    color:white;
}

.navbar h2{
    font-size:24px;
}

.navbar a{
    color:white;
    text-decoration:none;
    margin-left:15px;
    font-weight:bold;
}

.wrapper{
    width:92%;
    max-width:700px;
    margin:35px auto;
}

.card{
    background:white;
    border-radius:20px;
    padding:30px;
    box-shadow:0 15px 35px rgba(0,0,0,0.15);
}

h2{
    color:#222;
    margin-bottom:10px;
}

p{
    color:#666;
    margin-bottom:20px;
}

label{
    display:block;
    margin:12px 0 6px;
    font-weight:bold;
    color:#444;
}

select{
    width:100%;
    padding:13px;
    border:1px solid #ccc;
    border-radius:10px;
    font-size:15px;
    outline:none;
}

select:focus{
    border-color:#667eea;
}

button,.btn{
    display:inline-block;
    padding:13px 18px;
    border:none;
    border-radius:10px;
    background:#667eea;
    color:white;
    text-decoration:none;
    font-weight:bold;
    cursor:pointer;
    margin-top:18px;
}

button:hover,.btn:hover{
    background:#5563d8;
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

.loading{
    margin-top:10px;
    color:#555;
    display:none;
}
</style>
</head>
<body>

<div class="navbar">
    <h2>Book Request System</h2>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="wrapper">

    <div class="card">

        <h2>Request a Book</h2>
        <p>Select category, load books, and submit your request.</p>

        <?php if($message != ""){ ?>
            <div class="message <?php echo $type; ?>">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <label>Category</label>
            <select name="category" id="category" onchange="loadBooks()" required>
                <option value="">-- Select Category --</option>
                <option value="web development">App Development</option>
                <option value="mobile development">Mobile Development</option>
                <option value="artificial intelligence">AI</option>
            </select>

            <label>Book Title</label>
            <select name="book_title" id="bookList" required>
                <option value="">-- Books will appear here --</option>
            </select>

            <div class="loading" id="loading">Loading books...</div>

            <button type="submit" name="submit_request">Submit Request</button>
            <a href="dashboard.php" class="btn">Back</a>

        </form>

    </div>

</div>

<script>
function loadBooks(){

    let category = document.getElementById("category").value;
    let bookList = document.getElementById("bookList");
    let loading = document.getElementById("loading");

    bookList.innerHTML = '<option value="">-- Books will appear here --</option>';

    if(category === ""){
        return;
    }

    loading.style.display = "block";

    fetch("../api/fetch_books.php", {
        method: "POST",
        headers: {
            "Content-Type":"application/x-www-form-urlencoded"
        },
        body: "category=" + encodeURIComponent(category)
    })
    .then(response => response.json())
    .then(data => {

        loading.style.display = "none";

        if(data.length > 0){

            data.forEach(function(book){

                let option = document.createElement("option");
                option.value = book.title;
                option.textContent = book.title + " - " + book.author;
                bookList.appendChild(option);
            });

        } else {

            bookList.innerHTML = '<option value="">No books found</option>';
        }
    })
    .catch(error => {
        loading.style.display = "none";
        bookList.innerHTML = '<option value="">Error loading books</option>';
    });
}
</script>

</body>
</html>