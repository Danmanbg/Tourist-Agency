<?php
require '../config.php';
if (!is_admin()) {
    header('Location: ../login_user.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("DELETE FROM destinations WHERE id = ?");
$stmt->execute([$id]);

header('Location: admin_destinations.php?deleted=1');
exit;
?>
