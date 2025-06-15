<?php
require_once __DIR__ . '/includes/config.php';

try {
    $db = Config::getDB();
    echo "Database connection successful!";
    echo "\n\nDatabase Info:";
    echo "\n- Database name: " . Config::DB_NAME;
    echo "\n- Connected to MySQL server";
    
    // Check if users table exists and has data
    $stmt = $db->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    echo "\n- Found {$userCount} user(s) in the database";
    
    // Check if sample data exists
    $stmt = $db->query("SELECT COUNT(*) FROM jobs");
    $jobCount = $stmt->fetchColumn();
    echo "\n- Found {$jobCount} job listing(s)";
    
    echo "\n\nEverything is working correctly! You can access your site at: " . Config::BASE_URL;
} catch (Exception $e) {
    echo "Database connection error: " . $e->getMessage();
}
?> 