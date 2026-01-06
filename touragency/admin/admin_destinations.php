<?php
require '../config.php';
if (!is_admin()) {
    header('Location: ../login_user.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM destinations ORDER BY id DESC");
$destinations = $stmt->fetchAll();
?>
<!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<title>Управление на дестинации</title>
<link rel="stylesheet" href="../styles.css">
</head>
<body>
<header>
  <h1>Дестинации</h1>
  <nav>
    <a href="admin_dashboard.php">Админ</a>
    <a href="add_tour.php">Добави дестинация</a>
    <a href="../logout.php">Изход</a>
  </nav>
</header>

<div class="container">
  <h2>Всички дестинации</h2>
  <table>
    <tr><th>ID</th><th>Име</th><th>Цена</th><th>Действия</th></tr>
    <?php foreach ($destinations as $d): ?>
      <tr>
        <td><?=$d['id']?></td>
        <td><?=htmlspecialchars($d['title'])?></td>
        <td><?=number_format($d['price'],2)?> лв</td>
        <td>
          <a class="btn" href="edit_tour.php?id=<?=$d['id']?>">Редакция</a>
          <a class="btn delete" href="delete_tour.php?id=<?=$d['id']?>" onclick="return confirm('Изтриване на дестинацията?');">Изтрий</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
</body>
</html>

