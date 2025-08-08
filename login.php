<?php
$host = 'your-db-host';
$db   = 'risasi-users';
$user = 'your-db-user';
$pass = 'your-db-password';
$charset = 'utf8mb4';

$dsn = "pgsql:host=$host;dbname=$db;options='--client_encoding=$charset'";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit('Database connection failed.');
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    echo "Login successful. Welcome, comrade!";
    // Redirect to homepage or dashboard
    // header("Location: ../homepage/index.html");
} else {
    echo "Invalid credentials.";
}
?>