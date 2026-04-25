<?php
require '../config/db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];
$category = trim($_POST['category'] ?? '');

if ($category == '') {
    echo json_encode([]);
    exit();
}

/* Limit: max 5 calls in 24 hours */
$limitCheck = $pdo->prepare("
    SELECT COUNT(*) FROM api_logs
    WHERE user_id = ?
    AND called_at >= NOW() - INTERVAL 1 DAY
");
$limitCheck->execute([$user_id]);
$count = $limitCheck->fetchColumn();

if ($count >= 5) {
    echo json_encode([]);
    exit();
}

/* Save API log */
$log = $pdo->prepare("INSERT INTO api_logs (user_id) VALUES (?)");
$log->execute([$user_id]);

$url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($category);

$response = @file_get_contents($url);

if ($response === false) {
    echo json_encode([]);
    exit();
}

$data = json_decode($response, true);

$result = [];

if (isset($data['items'])) {

    foreach ($data['items'] as $item) {

        $title = $item['volumeInfo']['title'] ?? 'No Title';
        $author = $item['volumeInfo']['authors'][0] ?? 'Unknown';

        $save = $pdo->prepare("
            INSERT IGNORE INTO books (title, author, category)
            VALUES (?, ?, ?)
        ");
        $save->execute([$title, $author, $category]);

        $result[] = [
            "title" => $title,
            "author" => $author
        ];
    }
}

echo json_encode($result);