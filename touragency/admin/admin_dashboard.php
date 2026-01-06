<?php
require '../config.php';
if (!is_admin()) {
    header('Location: ../login_user.php');
    exit;
}

// Брой потребители, дестинации, резервации
$users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$destinations = $pdo->query("SELECT COUNT(*) FROM destinations")->fetchColumn();
$reservations = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
?>
<!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<title>Админ панел</title>
<link rel="stylesheet" href="../styles.css">
</head>
<body>
<header>
  <h1>Админ панел</h1>
  <nav>
    <a href="admin_dashboard.php">Начало</a>
    <a href="admin_destinations.php">Дестинации</a>
    <a href="admin_users.php">Потребители</a>
    <a href="view_reservations.php">Резервации</a>
    <a href="../logout.php">Изход</a>
  </nav>
</header>

<div class="container">
  <h2>Добре дошли, <?=htmlspecialchars($_SESSION['full_name'])?></h2>
  <p>Обща статистика:</p>
  <ul>
    <li>Потребители: <strong><?=$users?></strong></li>
    <li>Дестинации: <strong><?=$destinations?></strong></li>
    <li>Резервации: <strong><?=$reservations?></strong></li>
  </ul>
</div>
</body>
</html>

