<?php
require 'config.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM destinations WHERE id = ?');
$stmt->execute([$id]);
$d = $stmt->fetch();
if (!$d) {
    exit('Дестинацията не е намерена.');
}

$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_logged_in()) {
        header('Location: login_user.php');
        exit;
    }
    $date_from = $_POST['date_from'] ?? '';
    $date_to = $_POST['date_to'] ?? '';
    $guests = max(1, intval($_POST['guests'] ?? 1));

    if (!$date_from || !$date_to) $errors[] = 'Попълнете дати.';
    if ($date_from > $date_to) $errors[] = 'Началната дата трябва да е преди крайната.';

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO reservations (user_id, destination_id, date_from, date_to, guests) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$_SESSION['user_id'], $d['id'], $date_from, $date_to, $guests]);
        $success = true;
    }
}
?>
<!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<title><?=htmlspecialchars($d['title'])?></title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<a href="index.php">← Назад</a>
<h2><?=htmlspecialchars($d['title'])?></h2>
<img src="<?=htmlspecialchars($d['image'])?>" alt="">
<p><?=nl2br(htmlspecialchars($d['description']))?></p>
<p class="price"><?=number_format($d['price'],2)?> лв</p>

<?php if ($success): ?>
  <p class="success">Резервацията е записана успешно.</p>
<?php else: ?>
  <?php if ($errors) foreach ($errors as $e) echo "<p class='error'>$e</p>"; ?>
  <form method="post">
    <label>От дата</label><input type="date" name="date_from" required>
    <label>До дата</label><input type="date" name="date_to" required>
    <label>Брой гости</label><input type="number" name="guests" value="1" min="1">
    <button type="submit">Резервирай</button>
  </form>
<?php endif; ?>

</body>
</html>

