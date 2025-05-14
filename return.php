<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trans_id = $_POST['trans_id'];

    // Mark transaction returned
    $stmt1 = $pdo->prepare("UPDATE transactions SET returned_at = NOW() WHERE id = ?");
    $stmt1->execute([$trans_id]);

    // Make book available again
    $stmt2 = $pdo->prepare("SELECT book_id FROM transactions WHERE id = ?");
    $stmt2->execute([$trans_id]);
    $book_id = $stmt2->fetchColumn();

    $stmt3 = $pdo->prepare("UPDATE books SET available = 1 WHERE id = ?");
    $stmt3->execute([$book_id]);
}
header('Location: dashboard.php');
exit;
?>