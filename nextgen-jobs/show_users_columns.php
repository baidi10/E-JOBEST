<?php
require_once 'includes/config.php';
require_once 'classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    $stmt = $db->query("SHOW COLUMNS FROM users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Users Table Columns:\n\n";
    
    foreach ($columns as $column) {
        echo "Column: " . $column['Field'] . 
             "\n  Type: " . $column['Type'] . 
             "\n  Null: " . $column['Null'] . 
             "\n  Key: " . $column['Key'] . 
             "\n  Default: " . ($column['Default'] ?? 'NULL') . 
             "\n  Extra: " . $column['Extra'] . 
             "\n\n";
    }
    
    // Specifically check for resetToken and resetTokenExpires
    echo "Checking for specific columns:\n";
    $resetTokenExists = false;
    $resetTokenExpiresExists = false;
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'resetToken') {
            $resetTokenExists = true;
        }
        if ($column['Field'] === 'resetTokenExpires') {
            $resetTokenExpiresExists = true;
        }
    }
    
    echo "resetToken column exists: " . ($resetTokenExists ? "YES" : "NO") . "\n";
    echo "resetTokenExpires column exists: " . ($resetTokenExpiresExists ? "YES" : "NO") . "\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?> 