<?php
// Script to fix the admin password
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/classes/Database.php';

// Generate a bcrypt hash for 'admin123'
$passwordHash = password_hash('admin123', PASSWORD_BCRYPT);

// Connect to the database
$db = Database::getInstance()->getConnection();

// Update the admin user
$stmt = $db->prepare("UPDATE users SET passwordHash = ? WHERE email = 'admin@example.com'");
$result = $stmt->execute([$passwordHash]);

// Check the result
if ($result) {
    echo "Password for admin@example.com updated successfully. The new password is 'admin123'.";
} else {
    echo "Failed to update password.";
}

// Verify the update
$stmt = $db->prepare("SELECT userId, email, passwordHash, userType FROM users WHERE email = 'admin@example.com'");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "\n\nUser details:\n";
    echo "User ID: " . $user['userId'] . "\n";
    echo "Email: " . $user['email'] . "\n";
    echo "User Type: " . $user['userType'] . "\n";
    echo "Password Hash Length: " . strlen($user['passwordHash']) . "\n";
} 