<?php
require '../config.php';
if (!is_admin()) {
    header('Location: ../login_user.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
$stmt->execute([$id]);
$tour = $stmt->fetch();
if (!$tour) exit("Няма такава дестинация.");

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $imagePath = $tour['image'];

    if (!$title || !$description || $price <= 0) {
        $errors[] = "Всички полета са задължителни.";
    }

    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','gif'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $imagePath = '../assets/uploads/' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        } else {
            $errors[] = "Невалиден формат на изображението.";
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare("UPDATE destinations SET title=?, description=?, price=?, image=? WHERE id=?");
        $stmt->execute([$title, $description, $price, $imagePath, $id]);
        $success = "Дестинацията е обновена успешно.";
    }
}
?>
<!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<title>Редакция на дестинация</title>
<link rel="stylesheet" href="../styles.css">
</head>
<body>
<header>
  <h1>Редакция на дестинация</h1>
  <nav>
    <a href="admin_destinations.php">Назад</a>
  </nav>
</header>

<div class="container">
  <?php foreach ($errors as $e) echo "<p class='error'>$e</p>"; ?>
  <?php if ($success) echo "<p class='success'>$success</p>"; ?>

  <form method="post" enctype="multipart/form-data">
    <label>Име:</label>
    <input type="text" name="title" value="<?=htmlspecialchars($tour['title'])?>" required>
    <label>Описание:</label>
    <textarea name="description" rows="5" required><?=htmlspecialchars($tour['description'])?></textarea>
    <label>Цена (лв):</label>
    <input type="number" step="0.01" name="price" value="<?=htmlspecialchars($tour['price'])?>" required>
    <label>Снимка:</label>
    <?php if ($tour['image']): ?>
      <p><img src="<?=$tour['image']?>" alt="" style="width:120px;height:80px;object-fit:cover;"></p>
    <?php endif; ?>
    <input type="file" name="image" accept="image/*">
    <button type="submit">Запази</button>
  </form>
</div>
</body>
</html>
