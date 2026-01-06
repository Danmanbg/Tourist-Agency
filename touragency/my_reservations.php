<?php
require 'config.php';

if (!is_logged_in()) {
    header('Location: login_user.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Изтриване на резервация (ако е натиснат бутон)
if (isset($_GET['delete'])) {
    $res_id = intval($_GET['delete']);
    // Проверка дали резервацията принадлежи на текущия потребител
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ? AND user_id = ?");
    $stmt->execute([$res_id, $user_id]);
    header('Location: my_reservations.php?deleted=1');
    exit;
}

// Извличане на резервации
$stmt = $pdo->prepare("
    SELECT r.id, r.date_from, r.date_to, r.guests,
           d.title, d.price, d.image
    FROM reservations r
    JOIN destinations d ON r.destination_id = d.id
    WHERE r.user_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll();
?>
<!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<title>Моите резервации</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
  <h1>Моите резервации</h1>
  <nav>
    <a href="index.php">Начало</a>
    <a href="profile.php">Профил</a>
    <a href="logout.php">Изход</a>
  </nav>
</header>

<div class="container">
  <?php if (isset($_GET['deleted'])) echo "<p class='success'>Резервацията беше анулирана успешно.</p>"; ?>

  <?php if (!$reservations): ?>
    <p>Нямате направени резервации.</p>
  <?php else: ?>
    <table border="1" width="100%" cellpadding="6" cellspacing="0">
      <tr style="background:#eee;">
        <th>Дестинация</th>
        <th>Период</th>
        <th>Гости</th>
        <th>Цена (лв)</th>
        <th>Действие</th>
      </tr>
      <?php foreach ($reservations as $r): ?>
        <tr>
          <td>
            <img src="<?=htmlspecialchars($r['image'])?>" alt="" style="width:80px;height:50px;object-fit:cover;vertical-align:middle;">
            <?=htmlspecialchars($r['title'])?>
          </td>
          <td><?=htmlspecialchars($r['date_from'])?> → <?=htmlspecialchars($r['date_to'])?></td>
          <td><?=htmlspecialchars($r['guests'])?></td>
          <td><?=number_format($r['price'], 2)?></td>
          <td><a class="btn" style="background:#d33;" href="?delete=<?=$r['id']?>" onclick="return confirm('Сигурни ли сте, че искате да анулирате тази резервация?');">Изтрий</a></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
