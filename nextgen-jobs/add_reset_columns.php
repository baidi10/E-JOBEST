<?php
require_once 'includes/config.php';
require_once 'classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // First check if the columns exist
    $resetTokenExists = false;
    $resetTokenExpiresExists = false;
    
    $stmt = $db->query("SHOW COLUMNS FROM users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'resetToken') {
            $resetTokenExists = true;
        }
        if ($column['Field'] === 'resetTokenExpires') {
            $resetTokenExpiresExists = true;
        }
    }
    
    // Add resetToken column if it doesn't exist
    if (!$resetTokenExists) {
        echo "Adding resetToken column...\n";
        $db->exec("ALTER TABLE users ADD COLUMN resetToken VARCHAR(64) NULL");
        echo "resetToken column added successfully!\n";
    } else {
        echo "resetToken column already exists.\n";
    }
    
    // Add resetTokenExpires column if it doesn't exist
    if (!$resetTokenExpiresExists) {
        echo "Adding resetTokenExpires column...\n";
        $db->exec("ALTER TABLE users ADD COLUMN resetTokenExpires DATETIME NULL");
        echo "resetTokenExpires column added successfully!\n";
    } else {
        echo "resetTokenExpires column already exists.\n";
    }
    
    // Verify the columns now exist
    echo "\nUsers Table Structure:\n";
    echo str_repeat('-', 80) . "\n";
    echo sprintf("%-20s %-30s %-7s %-7s %s\n", 'Field', 'Type', 'Null', 'Key', 'Default');
    echo str_repeat('-', 80) . "\n";
    
    $stmt = $db->query("SHOW COLUMNS FROM users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo sprintf(
            "%-20s %-30s %-7s %-7s %s\n",
            $column['Field'],
            $column['Type'],
            $column['Null'],
            $column['Key'],
            $column['Default'] ?? 'NULL'
        );
    }
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?> 