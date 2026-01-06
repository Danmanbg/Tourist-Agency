<?php
require '../config.php';
if (!is_admin()) {
    header('Location: ../login_user.php');
    exit;
}

if (isset($_GET['delete'])) {
    $uid = intval($_GET['delete']);
    if ($uid != $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$uid]);
    }
    header('Location: admin_users.php');
    exit;
}

$users = $pdo->query("SELECT id, full_name, email, role, created_at FROM users ORDER BY id DESC")->fetchAll();
?>
<!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<title>Потребители</title>
<link rel="stylesheet" href="../styles.css">
</head>
<body>
<header>
  <h1>Управление на потребители</h1>
  <nav>
    <a href="admin_dashboard.php">Админ</a>
    <a href="../logout.php">Изход</a>
  </nav>
</header>

<div class="container">
  <table>
    <tr><th>ID</th><th>Име</th><th>Email</th><th>Роля</th><th>Дата</th><th>Действие</th></tr>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?=$u['id']?></td>
        <td><?=htmlspecialchars($u['full_name'])?></td>
        <td><?=htmlspecialchars($u['email'])?></td>
        <td><?=htmlspecialchars($u['role'])?></td>
        <td><?=$u['created_at']?></td>
        <td>
          <?php if ($u['id'] != $_SESSION['user_id']): ?>
            <a class="btn delete" href="?delete=<?=$u['id']?>" onclick="return confirm('Изтриване на потребител?');">Изтрий</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
</body>
</html>
