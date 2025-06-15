<?php
// add_sample_jobs.php - Script to add sample jobs to the database

// Load dependencies
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/classes/Database.php';

// Connect to database
$db = Database::getInstance()->getConnection();

echo "Adding sample jobs...\n";

// Get company IDs
$stmt = $db->query("SELECT companyId, companyName FROM companies");
$companies = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

if (empty($companies)) {
    die("No companies found. Please run add_sample_data.php first.\n");
}

// Sample jobs
$jobs = [
    [
        'companyName' => 'TechCorp',
        'jobTitle' => 'Frontend Developer',
        'jobDescription' => 'We are looking for a skilled frontend developer with experience in React and modern JavaScript.',
        'jobRequirements' => 'React, JavaScript, CSS, 3+ years experience',
        'jobBenefits' => 'Health insurance, flexible hours, remote work option',
        'jobType' => 'fullTime',
        'experienceLevel' => 'mid',
        'location' => 'San Francisco, CA',
        'isRemote' => 1,
        'salaryMin' => 80000,
        'salaryMax' => 110000,
        'isFeatured' => 1
    ],
    [
        'companyName' => 'StartupX',
        'jobTitle' => 'Mobile App Developer',
        'jobDescription' => 'Join our mobile team to build cutting-edge iOS and Android applications.',
        'jobRequirements' => 'Swift, Kotlin, React Native, 2+ years experience',
        'jobBenefits' => 'Competitive salary, equity, flexible work hours',
        'jobType' => 'fullTime',
        'experienceLevel' => 'mid',
        'location' => 'Austin, TX',
        'isRemote' => 0,
        'salaryMin' => 85000,
        'salaryMax' => 115000,
        'isFeatured' => 1
    ],
    [
        'companyName' => 'AI Labs',
        'jobTitle' => 'Machine Learning Engineer',
        'jobDescription' => 'Work on cutting-edge AI models and machine learning algorithms.',
        'jobRequirements' => 'Python, TensorFlow, PyTorch, ML experience',
        'jobBenefits' => 'Health benefits, 401k, flexible work arrangements',
        'jobType' => 'fullTime',
        'experienceLevel' => 'senior',
        'location' => 'Boston, MA',
        'isRemote' => 1,
        'salaryMin' => 120000,
        'salaryMax' => 160000,
        'isFeatured' => 1
    ],
    [
        'companyName' => 'CloudSoft',
        'jobTitle' => 'DevOps Engineer',
        'jobDescription' => 'Help us build and maintain our cloud infrastructure and CI/CD pipelines.',
        'jobRequirements' => 'AWS, Docker, Kubernetes, Terraform',
        'jobBenefits' => 'Remote work, flexible hours, professional development budget',
        'jobType' => 'fullTime',
        'experienceLevel' => 'mid',
        'location' => 'Seattle, WA',
        'isRemote' => 1,
        'salaryMin' => 95000,
        'salaryMax' => 130000,
        'isFeatured' => 1
    ]
];

// Insert jobs
$stmt = $db->prepare("
    INSERT INTO jobs (
        companyId, postedBy, jobTitle, jobSlug, jobDescription, 
        jobRequirements, jobBenefits, jobType, experienceLevel, 
        location, isRemote, salaryMin, salaryMax, salaryCurrency, 
        salaryPeriod, isSalaryVisible, isFeatured, expiresAt, createdAt
    ) VALUES (
        :companyId, :postedBy, :jobTitle, :jobSlug, :jobDescription, 
        :jobRequirements, :jobBenefits, :jobType, :experienceLevel, 
        :location, :isRemote, :salaryMin, :salaryMax, 'USD', 
        'yearly', 1, :isFeatured, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW()
    )
");

foreach ($jobs as $job) {
    // Find company ID
    $companyId = array_search($job['companyName'], $companies);
    if (!$companyId) {
        echo "Company {$job['companyName']} not found, skipping job.\n";
        continue;
    }
    
    // Create slug
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', $job['jobTitle']), '-'));
    
    // Check if slug exists
    $checkStmt = $db->prepare("SELECT COUNT(*) FROM jobs WHERE jobSlug LIKE ?");
    $checkStmt->execute([$slug . '%']);
    $count = $checkStmt->fetchColumn();
    
    if ($count > 0) {
        $slug = $slug . '-' . ($count + 1);
    }
    
    try {
        $stmt->execute([
            ':companyId' => $companyId,
            ':postedBy' => 1, // Admin user
            ':jobTitle' => $job['jobTitle'],
            ':jobSlug' => $slug,
            ':jobDescription' => $job['jobDescription'],
            ':jobRequirements' => $job['jobRequirements'],
            ':jobBenefits' => $job['jobBenefits'],
            ':jobType' => $job['jobType'],
            ':experienceLevel' => $job['experienceLevel'],
            ':location' => $job['location'],
            ':isRemote' => $job['isRemote'],
            ':salaryMin' => $job['salaryMin'],
            ':salaryMax' => $job['salaryMax'],
            ':isFeatured' => $job['isFeatured']
        ]);
        
        $jobId = $db->lastInsertId();
        echo "Added job: {$job['jobTitle']} (ID: $jobId)\n";
        
        // Add skills based on requirements
        $skills = explode(',', $job['jobRequirements']);
        $skillStmt = $db->prepare("
            INSERT INTO jobSkills (jobId, skillName, createdAt)
            VALUES (:jobId, :skillName, NOW())
        ");
        
        foreach ($skills as $skill) {
            $skill = trim($skill);
            // Skip if it looks like years of experience
            if (preg_match('/\d+\+?\s*years?/i', $skill)) {
                continue;
            }
            
            try {
                $skillStmt->execute([
                    ':jobId' => $jobId,
                    ':skillName' => $skill
                ]);
                echo "  - Added skill: $skill\n";
            } catch (Exception $e) {
                echo "  - Error adding skill $skill: " . $e->getMessage() . "\n";
            }
        }
        
    } catch (PDOException $e) {
        echo "Error adding job {$job['jobTitle']}: " . $e->getMessage() . "\n";
    }
}

echo "Sample jobs added successfully!\n"; 