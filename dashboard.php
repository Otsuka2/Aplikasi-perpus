<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

// Available books
$books = $pdo->query("SELECT * FROM books WHERE available = 1")->fetchAll(PDO::FETCH_ASSOC);

// Borrowed books
$stmt = $pdo->prepare(
    "SELECT t.id AS trans_id, b.* FROM transactions t
     JOIN books b ON t.book_id = b.id
     WHERE t.user_id = ? AND t.returned_at IS NULL"
);
$stmt->execute([$user_id]);
$borrowed = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Perpustakaan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Perpustakaan Online</h1>
        <a href="logout.php">Logout</a>
    </header>

    <section>
        <br>
        <br>
        <h2>Buku Tersedia</h2>
        <table>
            <tr><th>Judul</th><th>Penulis</th><th>Aksi</th></tr>
            <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td>
                    <form method="post" action="borrow.php">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        <button class="btn btn-green">Pinjam</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <section>
        <h2>Buku Dipinjam</h2>
        <table>
            <tr><th>Judul</th><th>Penulis</th><th>Aksi</th></tr>
            <?php foreach ($borrowed as $b): ?>
            <tr>
                <td><?= htmlspecialchars($b['title']) ?></td>
                <td><?= htmlspecialchars($b['author']) ?></td>
                <td>
                    <form method="post" action="return.php">
                        <input type="hidden" name="trans_id" value="<?= $b['trans_id'] ?>">
                        <button class="btn btn-red">Kembalikan</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <script src="script.js"></script>
</body>
</html>