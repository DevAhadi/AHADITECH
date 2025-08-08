<?php
$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$charset = 'utf8mb4';

$dsn = "pgsql:host=$host;dbname=$db;options='--client_encoding=$charset'";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Validate inputs
$code = $_POST['code'] ?? '';
$newPasswordRaw = $_POST['new_password'] ?? '';

if (empty($code) || empty($newPasswordRaw)) {
    die("Missing reset code or new password.");
}

// Hash the new password
$newPassword = password_hash($newPasswordRaw, PASSWORD_DEFAULT);

// Update password and clear reset code
$stmt = $pdo->prepare("UPDATE users SET password = $1, reset_code = NULL WHERE reset_code = $2");
$stmt->execute([$newPassword, $code]);

if ($stmt->rowCount() > 0) {
    echo "✅ Password updated successfully!";
} else {
    echo "❌ Invalid or expired reset code.";
}
?>
