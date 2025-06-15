<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/classes/Database.php';

// NOTE: Admin restriction temporarily removed for setup
// session_start();
// if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
//     echo "This script must be run by an administrator.";
//     exit;
// }

echo "<h1>Creating Skills Tables</h1>";

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if skills table already exists
    $result = $db->query("SHOW TABLES LIKE 'skills'");
    $skillsTableExists = $result->rowCount() > 0;
    
    // Check if user_skills table already exists
    $result = $db->query("SHOW TABLES LIKE 'user_skills'");
    $userSkillsTableExists = $result->rowCount() > 0;
    
    // Begin transaction
    $db->beginTransaction();
    
    if (!$skillsTableExists) {
        // Create skills table
        $db->exec("
            CREATE TABLE `skills` (
                `skillId` int(11) NOT NULL AUTO_INCREMENT,
                `skillName` varchar(100) NOT NULL,
                `category` varchar(50) DEFAULT NULL,
                `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`skillId`),
                UNIQUE KEY `skillName` (`skillName`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");
        echo "<p>Created skills table.</p>";
    } else {
        echo "<p>Skills table already exists.</p>";
    }
    
    if (!$userSkillsTableExists) {
        // Create user_skills table
        $db->exec("
            CREATE TABLE `user_skills` (
                `userSkillId` int(11) NOT NULL AUTO_INCREMENT,
                `userId` int(11) NOT NULL,
                `skillId` int(11) NOT NULL,
                `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`userSkillId`),
                UNIQUE KEY `user_skill_unique` (`userId`, `skillId`),
                KEY `skillId` (`skillId`),
                CONSTRAINT `user_skills_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
                CONSTRAINT `user_skills_ibfk_2` FOREIGN KEY (`skillId`) REFERENCES `skills` (`skillId`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");
        echo "<p>Created user_skills table.</p>";
    } else {
        echo "<p>User_skills table already exists.</p>";
    }
    
    // Add some sample skills
    $sampleSkills = [
        'PHP', 'JavaScript', 'HTML', 'CSS', 'React', 'Vue.js', 'Angular', 
        'Node.js', 'Python', 'Java', 'C#', 'MySQL', 'MongoDB', 'SQL Server',
        'Docker', 'Kubernetes', 'AWS', 'Azure', 'Git', 'Laravel', 'Django',
        'Spring Boot', 'Express.js', 'TypeScript', 'jQuery', 'Bootstrap',
        'Tailwind CSS', 'WordPress', 'Photoshop', 'Illustrator', 'Figma',
        'Project Management', 'Agile', 'Scrum', 'SEO', 'Digital Marketing'
    ];
    
    $addedSkills = 0;
    
    // Add sample skills if the table is empty
    $result = $db->query("SELECT COUNT(*) FROM skills");
    $skillCount = $result->fetchColumn();
    
    if ($skillCount == 0) {
        $stmt = $db->prepare("INSERT IGNORE INTO skills (skillName) VALUES (?)");
        
        foreach ($sampleSkills as $skill) {
            $stmt->execute([$skill]);
            if ($stmt->rowCount() > 0) {
                $addedSkills++;
            }
        }
        
        echo "<p>Added $addedSkills sample skills.</p>";
    } else {
        echo "<p>Skills already exist in the database.</p>";
    }
    
    // Commit transaction
    $db->commit();
    
    echo "<p>Database setup completed successfully.</p>";
    echo "<p><a href='/pages/public/index.php'>Return to Homepage</a></p>";
    
} catch (PDOException $e) {
    // Rollback transaction on error
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    
    echo "<p>Error: " . $e->getMessage() . "</p>";
} 