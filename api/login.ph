<?php
// Load environment variables
$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$charset = 'utf8';

$dsn = "pgsql:host=$host;dbname=$db;options='--client_encoding=$charset'";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}

// Validate and sanitize input
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
    http_response_code(400);
    echo "Invalid email or password.";
    exit;
}

// Fetch user from database
$stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo "Incorrect email or password.";
    exit;
}

// Optional: Start session or redirect
session_start();
$_SESSION['user_id'] = $user['id'];

http_response_code(200);
echo "Login successful. Welcome!";
?>
