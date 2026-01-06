<?php
require 'config.php';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Валидации
    if (strlen($full_name) < 2) {
        $errors[] = "Името трябва да съдържа поне 2 символа.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Въведете валиден email.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Паролата трябва да е поне 6 символа.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Паролите не съвпадат.";
    }

    // Проверка за съществуващ email
    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Този email вече е регистриран.";
        }
    }

    // Вкарване в базата
    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
        $stmt->execute([$full_name, $email, $hash]);
        $success = "Регистрацията е успешна. Можете да влезете в системата.";
        // Пренасочване към login със съобщение
        header('Location: login_user.php?registered=1');
        exit;
    }
}
?>
<!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<title>Регистрация</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
  <h2>Регистрация</h2>

  <?php if ($errors): ?>
    <?php foreach ($errors as $e): ?>
      <p class="error"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if ($success): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
  <?php endif; ?>

  <form method="post">
    <label>Име</label>
    <input type="text" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

    <label>Парола</label>
    <input type="password" name="password" required>

    <label>Повтори паролата</label>
    <input type="password" name="confirm_password" required>

    <button type="submit">Регистрация</button>
  </form>

  <p>Вече имате акаунт? <a href="login_user.php">Вход</a></p>
</div>
</body>
</html>
