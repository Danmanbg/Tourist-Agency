<?php
require 'config.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Въведете валиден email.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id, password, role, full_name FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Успешен вход
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];

            // Пренасочване според роля
            if ($user['role'] === 'admin') {
                header('Location: admin/admin_dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $errors[] = 'Невалиден email или парола.';
        }
    }
}
?>
<!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<title>Вход</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
  <h2>Вход</h2>

  <?php if ($errors): ?>
    <?php foreach ($errors as $e): ?>
      <p class="error"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if (isset($_GET['registered'])): ?>
    <p class="success">Регистрацията е успешна. Влезте в системата.</p>
  <?php endif; ?>

  <form method="post">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Парола</label>
    <input type="password" name="password" required>

    <button type="submit">Вход</button>
  </form>

  <p>Нямате акаунт? <a href="register.php">Регистрация</a></p>
</div>
</body>
</html>
