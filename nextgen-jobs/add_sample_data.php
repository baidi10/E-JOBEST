<?php
// add_sample_data.php - Script to add sample data to the database

// Load dependencies
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/classes/Database.php';

// Connect to database
$db = Database::getInstance()->getConnection();

echo "Adding sample companies...\n";

// Add sample companies
$companies = [
    [
        'userId' => 1,
        'companyName' => 'StartupX',
        'companySlug' => 'startupx',
        'logo' => 'company-2.png',
        'description' => 'An innovative startup focused on mobile applications',
        'industry' => 'Technology',
        'employeeCount' => '10-50',
        'foundedYear' => 2018,
        'headquarters' => 'Austin, TX',
        'websiteUrl' => 'https://example.com',
        'isVerified' => 1
    ],
    [
        'userId' => 1,
        'companyName' => 'AI Labs',
        'companySlug' => 'ai-labs',
        'logo' => 'company-3.png',
        'description' => 'AI research company developing cutting-edge machine learning solutions',
        'industry' => 'Artificial Intelligence',
        'employeeCount' => '50-200',
        'foundedYear' => 2015,
        'headquarters' => 'Boston, MA',
        'websiteUrl' => 'https://example.com',
        'isVerified' => 1
    ],
    [
        'userId' => 1,
        'companyName' => 'CloudSoft',
        'companySlug' => 'cloudsoft',
        'logo' => 'company-4.png',
        'description' => 'Cloud infrastructure and DevOps solutions provider',
        'industry' => 'Cloud Computing',
        'employeeCount' => '100-500',
        'foundedYear' => 2012,
        'headquarters' => 'Seattle, WA',
        'websiteUrl' => 'https://example.com',
        'isVerified' => 1
    ]
];

// Insert companies
$stmt = $db->prepare("
    INSERT INTO companies (
        userId, companyName, companySlug, logo, description, 
        industry, employeeCount, foundedYear, headquarters, 
        websiteUrl, isVerified, createdAt
    ) VALUES (
        :userId, :companyName, :companySlug, :logo, :description,
        :industry, :employeeCount, :foundedYear, :headquarters,
        :websiteUrl, :isVerified, NOW()
    )
");

foreach ($companies as $company) {
    try {
        $stmt->execute($company);
        echo "Added company: {$company['companyName']}\n";
    } catch (PDOException $e) {
        // Skip if company already exists (duplicate key)
        if ($e->getCode() == 23000) {
            echo "Company {$company['companyName']} already exists, skipping.\n";
        } else {
            echo "Error adding company {$company['companyName']}: " . $e->getMessage() . "\n";
        }
    }
}

echo "Sample data added successfully!\n"; 