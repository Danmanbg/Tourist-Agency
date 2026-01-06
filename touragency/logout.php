<?php
session_start();

// Изчистване на всички сесийни данни
$_SESSION = [];
session_unset();
session_destroy();

// Пренасочване към началната страница
header('Location: index.php');
exit;
?>
