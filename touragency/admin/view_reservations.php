<?php
require '../config.php';
if (!is_admin()) {
    header('Location: ../login_user.php');
    exit;
}

$stmt = $pdo->query("
    SELECT r.id, r.date_from, r.date_to, r.guests, r.created_at,
           u.full_name AS user_name, u.email,
           d.title AS destination, d.price
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN destinations d ON r.destination_id = d.id
    ORDER BY r.created_at DESC
");
$reservations = $stmt->fetchAll();
?>
<!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<title>Всички резервации</title>
<link rel="stylesheet" href="../styles.css">
</head>
<body>
<header>
  <h1>Всички резервации</h1>
  <nav>
    <a href="admin_dashboard.php">Админ</a>
    <a href="../logout.php">Изход</a>
  </nav>
</header>

<div class="container">
  <?php if (!$reservations): ?>
    <p>Няма резервации в системата.</p>
  <?php else: ?>
    <table>
      <tr>
        <th>ID</th>
        <th>Потребител</th>
        <th>Email</th>
        <th>Дестинация</th>
        <th>Период</th>
        <th>Гости</th>
        <th>Цена (лв)</th>
        <th>Дата на създаване</th>
      </tr>
      <?php foreach ($reservations as $r): ?>
        <tr>
          <td><?=$r['id']?></td>
          <td><?=htmlspecialchars($r['user_name'])?></td>
          <td><?=htmlspecialchars($r['email'])?></td>
          <td><?=htmlspecialchars($r['destination'])?></td>
          <td><?=$r['date_from']?> → <?=$r['date_to']?></td>
          <td><?=$r['guests']?></td>
          <td><?=number_format($r['price'],2)?></td>
          <td><?=$r['created_at']?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
