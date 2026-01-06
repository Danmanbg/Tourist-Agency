<?php
require 'config.php';

$stmt = $pdo->query('SELECT * FROM destinations ORDER BY created_at DESC');
$destinations = $stmt->fetchAll();
?>
<!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<title>Туристическа агенция</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
  <h1>Туристическа агенция</h1>
  <nav>
    <?php if (is_logged_in()): ?>
      <a href="profile.php">Профил (<?=htmlspecialchars($_SESSION['full_name'])?>)</a>
      <a href="my_reservations.php">Моите резервации</a>
      <?php if (is_admin()): ?>
        <a href="admin/admin_dashboard.php">Админ</a>
      <?php endif; ?>
      <a href="logout.php">Изход</a>
    <?php else: ?>
      <a href="login_user.php">Вход</a>
      <a href="register.php">Регистрация</a>
    <?php endif; ?>
  </nav>
</header>

<main class="grid">
  <?php foreach ($destinations as $d): ?>
    <div class="card">
      <img src="<?=htmlspecialchars($d['image'] ?: 'assets/images/default.jpg')?>" alt="">
      <h3><?=htmlspecialchars($d['title'])?></h3>
      <p><?=htmlspecialchars(mb_substr($d['description'],0,120))?>...</p>
      <p class="price"><?=number_format($d['price'],2)?> лв</p>
      <a class="btn" href="destination.php?id=<?=$d['id']?>">Повече</a>
    </div>
  <?php endforeach; ?>
</main>
</body>
</html>
