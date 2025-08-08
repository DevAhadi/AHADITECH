<?php
$host = 'your-db-host';
$db   = 'risasi-users';
$user = 'your-db-user';
$pass = 'your-db-password';
$charset = 'utf8mb4';

$dsn = "pgsql:host=$host;dbname=$db;options='--client_encoding=$charset'";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$pdo = new PDO($dsn, $user, $pass, $options);

$code = $_POST['code'];
$newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = ?, reset_code = NULL WHERE reset_code = ?");
$stmt->execute([$newPassword, $code]);

echo "Password updated successfully!";
?>