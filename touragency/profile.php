<?php
require 'config.php';

if (!is_logged_in()) {
    header('Location: login_user.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Извличане на текущите данни
$stmt = $pdo->prepare("SELECT full_name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$success = '';
$errors = [];

// Ако е изпратена форма за промяна на име
if (isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    if (strlen($full_name) < 2) {
        $errors[] = "Името трябва да съдържа поне 2 символа.";
    }
    if (!$errors) {
        $stmt = $pdo->prepare("UPDATE users SET full_name = ? WHERE id = ?");
        $stmt->execute([$full_name, $user_id]);
        $_SESSION['full_name'] = $full_name;
        $success = "Профилът е обновен успешно.";
    }
}

// Ако е изпратена форма за промяна на парола
if (isset($_POST['change_password'])) {
    $current_pass = $_POST['current_pass'] ?? '';
    $new_pass = $_POST['new_pass'] ?? '';
    $confirm_pass = $_POST['confirm_pass'] ?? '';

    if (strlen($new_pass) < 6) {
        $errors[] = "Новата парола трябва да е поне 6 символа.";
    } elseif ($new_pass !== $confirm_pass) {
        $errors[] = "Паролите не съвпадат.";
    } else {
        // Проверка на текущата парола
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $row = $stmt->fetch();
        if (!$row || !password_verify($current_pass, $row['password'])) {
            $errors[] = "Текущата парола е грешна.";
        } else {
            $hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hash, $user_id]);
            $success = "Паролата е сменена успешно.";
        }
    }
}
?>
<!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<title>Моят профил</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
  <h1>Моят профил</h1>
  <nav>
    <a href="index.php">Начало</a>
    <a href="my_reservations.php">Моите резервации</a>
    <a href="logout.php">Изход</a>
  </nav>
</header>

<div class="container">
  <h2>Информация за профила</h2>

  <?php foreach ($errors as $e) echo "<p class='error'>$e</p>"; ?>
  <?php if ($success) echo "<p class='success'>$success</p>"; ?>

  <form method="post">
    <label>Име:</label>
    <input type="text" name="full_name" value="<?=htmlspecialchars($user['full_name'])?>">
    <label>Email:</label>
    <input type="email" value="<?=htmlspecialchars($user['email'])?>" readonly>
    <button type="submit" name="update_profile">Обнови профила</button>
  </form>

  <h3>Смяна на парола</h3>
  <form method="post">
    <label>Текуща парола:</label>
    <input type="password" name="current_pass" required>
    <label>Нова парола:</label>
    <input type="password" name="new_pass" required>
    <label>Повтори новата парола:</label>
    <input type="password" name="confirm_pass" required>
    <button type="submit" name="change_password">Смени паролата</button>
  </form>
</div>
</body>
</html>
