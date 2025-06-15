<?php
require_once 'includes/config.php';
require_once 'classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Add both columns in a single ALTER TABLE statement
    echo "Adding resetToken and resetTokenExpires columns...\n";
    $result = $db->exec("
        ALTER TABLE users 
        ADD COLUMN IF NOT EXISTS resetToken VARCHAR(64) NULL,
        ADD COLUMN IF NOT EXISTS resetTokenExpires DATETIME NULL
    ");
    
    echo "Columns added successfully!\n";
    
    // Verify the columns exist
    $stmt = $db->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nUsers Table Structure:\n";
    echo str_repeat('-', 80) . "\n";
    echo sprintf("%-20s %-30s %-7s %-7s %s\n", 'Field', 'Type', 'Null', 'Key', 'Default');
    echo str_repeat('-', 80) . "\n";
    
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