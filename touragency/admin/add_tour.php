<?php
require '../config.php';
if (!is_admin()) {
    header('Location: ../login_user.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $imagePath = null;

    if (!$title || !$description || $price <= 0) {
        $errors[] = "Всички полета са задължителни.";
    }

    // Качване на изображение
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','gif'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $imagePath = '../assets/images/' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        } else {
            $errors[] = "Позволени формати: jpg, jpeg, png, gif.";
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare("INSERT INTO destinations (title, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $price, $imagePath]);
        $success = "Дестинацията е добавена успешно.";
    }
}
?>
<!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<title>Добавяне на дестинация</title>
<link rel="stylesheet" href="../styles.css">
</head>
<body>
<header>
  <h1>Добавяне на дестинация</h1>
  <nav>
    <a href="admin_destinations.php">Назад</a>
  </nav>
</header>

<div class="container">
  <?php foreach ($errors as $e) echo "<p class='error'>$e</p>"; ?>
  <?php if ($success) echo "<p class='success'>$success</p>"; ?>

  <form method="post" enctype="multipart/form-data">
    <label>Име на дестинацията:</label>
    <input type="text" name="title" required>
    <label>Описание:</label>
    <textarea name="description" rows="5" required></textarea>
    <label>Цена (лв):</label>
    <input type="number" step="0.01" name="price" required>
    <label>Снимка:</label>
    <input type="file" name="image" accept="image/*">
    <button type="submit">Добави</button>
  </form>
</div>
</body>
</html>
