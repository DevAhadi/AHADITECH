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

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    http_response_code(400);
    echo "Invalid email or password too short.";
    exit;
}

// Hash the password securely
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo "Email already registered.";
    exit;
}

// Insert new user
$stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->execute([$email, $hashedPassword]);

http_response_code(200);
echo "Account created successfully!";
?>
