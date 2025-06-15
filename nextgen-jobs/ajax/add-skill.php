<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in and is a job seeker
if (!isLoggedIn() || !isJobSeeker()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check if it's a POST request and has the required data
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['skill'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

try {
    $user = new User();
    $skillName = trim($_POST['skill']);
    
    // Validate skill name
    if (strlen($skillName) < 2 || strlen($skillName) > 50) {
        echo json_encode(['success' => false, 'message' => 'Skill name must be between 2 and 50 characters']);
        exit;
    }
    
    // Add skill to user profile
    $result = $user->addSkill($_SESSION['user_id'], $skillName);
    
    if ($result) {
        echo json_encode([
            'success' => true, 
            'message' => 'Skill added successfully',
            'skill' => $skillName
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add skill']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 