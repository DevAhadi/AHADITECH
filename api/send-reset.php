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

$email = $_POST['email'];
$code = bin2hex(random_bytes(16)); // Secure reset code

$stmt = $pdo->prepare("UPDATE users SET reset_code = ? WHERE email = ?");
$stmt->execute([$code, $email]);

echo "Reset link: <a href='reset-password.html?code=$code'>Click here to reset</a>";
?>
