<?php
require_once 'includes/config.php';
require_once 'classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if resetToken column exists
    $stmt = $db->query("SHOW COLUMNS FROM users LIKE 'resetToken'");
    if ($stmt->rowCount() > 0) {
        echo "resetToken column exists\n";
    } else {
        echo "resetToken column does NOT exist! Adding it now...\n";
        $db->exec("ALTER TABLE users ADD COLUMN resetToken VARCHAR(64) NULL");
        echo "resetToken column added successfully\n";
    }
    
    // Check if resetTokenExpires column exists
    $stmt = $db->query("SHOW COLUMNS FROM users LIKE 'resetTokenExpires'");
    if ($stmt->rowCount() > 0) {
        echo "resetTokenExpires column exists\n";
    } else {
        echo "resetTokenExpires column does NOT exist! Adding it now...\n";
        $db->exec("ALTER TABLE users ADD COLUMN resetTokenExpires DATETIME NULL");
        echo "resetTokenExpires column added successfully\n";
    }
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?> 