<?php
// Стартиране на сесия
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Настройки за връзка с базата
$host = 'localhost';
$port = 3307;       // <-- добави тук порт
$db   = 'touragency';
$user = 'root';
$pass = '';         // XAMPP по подразбиране няма парола
$charset = 'utf8mb4';

// DSN и опции за PDO
$dsn = "mysql:host=$host;port=$port;dbname=$db;user=$user;pass=$pass;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
// Свързване с базата
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // За продукция: логвайте вместо директен exit
    exit('Database connection failed: ' . $e->getMessage());
}

// ----------------------
// HELPER ФУНКЦИИ
// ----------------------

// Проверка дали потребителят е логнат
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Проверка дали потребителят е администратор
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>
