<?php
// Script to create a new admin account
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/classes/Database.php';

// Admin credentials
$email = 'admin@nextgen-jobs.com';  // Unique email
$password = 'admin123';
$firstName = 'Admin';
$lastName = 'User';

// Generate a bcrypt hash for the password
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// Connect to the database
$db = Database::getInstance()->getConnection();

// Check if the email already exists
$checkStmt = $db->prepare("SELECT userId FROM users WHERE email = ?");
$checkStmt->execute([$email]);
$existingUser = $checkStmt->fetch();

if ($existingUser) {
    echo "An account with email '$email' already exists. No action taken.";
    exit;
}

// Insert the new admin user
$stmt = $db->prepare("
    INSERT INTO users (
        email, 
        passwordHash, 
        firstName, 
        lastName, 
        userType, 
        isEmailVerified, 
        createdAt,
        updatedAt
    ) 
    VALUES (
        ?, ?, ?, ?, 'admin', 1, NOW(), NOW()
    )
");

$result = $stmt->execute([
    $email,
    $passwordHash,
    $firstName,
    $lastName
]);

// Check the result
if ($result) {
    $userId = $db->lastInsertId();
    echo "Admin account created successfully!\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
    echo "User ID: $userId";
} else {
    echo "Failed to create admin account.";
} 