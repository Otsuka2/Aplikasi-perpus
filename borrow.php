<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];

    // Create transaction and mark unavailable
    $stmt1 = $pdo->prepare("INSERT INTO transactions (user_id, book_id) VALUES (?, ?)");
    $stmt1->execute([$user_id, $book_id]);

    $stmt2 = $pdo->prepare("UPDATE books SET available = 0 WHERE id = ?");
    $stmt2->execute([$book_id]);
}
header('Location: dashboard.php');
exit;
?>