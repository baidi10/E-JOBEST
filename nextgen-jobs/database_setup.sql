-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 08:21 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jobest`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `applicationId` int(11) NOT NULL,
  `jobId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `coverLetter` text DEFAULT NULL,
  `resumeUrl` varchar(255) DEFAULT NULL,
  `status` enum('pending','viewed','interviewing','rejected','hired') NOT NULL DEFAULT 'pending',
  `createdAt` datetime NOT NULL,
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `companyId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `companyName` varchar(255) NOT NULL,
  `companySlug` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `employeeCount` varchar(50) DEFAULT NULL,
  `foundedYear` int(11) DEFAULT NULL,
  `headquarters` varchar(100) DEFAULT NULL,
  `websiteUrl` varchar(255) DEFAULT NULL,
  `linkedinUrl` varchar(255) DEFAULT NULL,
  `twitterUrl` varchar(255) DEFAULT NULL,
  `isVerified` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`companyId`, `userId`, `companyName`, `companySlug`, `logo`, `photo`, `description`, `industry`, `employeeCount`, `foundedYear`, `headquarters`, `websiteUrl`, `linkedinUrl`, `twitterUrl`, `isVerified`, `createdAt`, `updatedAt`) VALUES
(1, 21, 'TechCorp', 'techcorp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(2, 23, 'HealthMed', 'healthmed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(3, 26, 'BuildPro', 'buildpro', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(4, 28, 'FinServe', 'finserve', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(5, 31, 'EduWorld', 'eduworld', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(6, 34, 'AgroPlus', 'agroplus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(7, 36, 'AutoMech', 'automech', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(8, 38, 'GreenEnergy', 'greenenergy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(9, 23, 'FastNet', 'fastnet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(10, 28, 'SkyHigh', 'skyhigh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(11, 31, 'CloudSoft', 'cloudsoft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(12, 34, 'CyberSafe', 'cybersafe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(13, 36, 'DataTrack', 'datatrack', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(14, 21, 'OptiTech', 'optitech', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(15, 23, 'MegaPharm', 'megapharm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(16, 26, 'LogiTrans', 'logitrans', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(17, 28, 'MediaWave', 'mediawave', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(18, 31, 'SolarSys', 'solarsys', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(19, 34, 'AquaLife', 'aqualife', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46'),
(20, 36, 'NanoTech', 'nanotech', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 06:30:46', '2025-05-19 05:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `cronlogs`
--

CREATE TABLE `cronlogs` (
  `logId` int(11) NOT NULL,
  `cronName` varchar(50) NOT NULL,
  `executionTime` datetime NOT NULL,
  `affectedRows` int(11) NOT NULL DEFAULT 0,
  `message` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dailystats`
--

CREATE TABLE `dailystats` (
  `statId` int(11) NOT NULL,
  `date` date NOT NULL,
  `newJobSeekers` int(11) NOT NULL DEFAULT 0,
  `newEmployers` int(11) NOT NULL DEFAULT 0,
  `newJobs` int(11) NOT NULL DEFAULT 0,
  `newApplications` int(11) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `jobId` int(11) NOT NULL,
  `companyId` int(11) NOT NULL,
  `postedBy` int(11) NOT NULL,
  `jobTitle` varchar(255) NOT NULL,
  `jobSlug` varchar(255) NOT NULL,
  `jobDescription` text NOT NULL,
  `jobPhoto` varchar(255) DEFAULT NULL,
  `jobRequirements` text NOT NULL,
  `jobBenefits` text DEFAULT NULL,
  `jobType` enum('fullTime','partTime','contract','freelance','internship') NOT NULL,
  `experienceLevel` enum('entry','mid','senior','lead','executive') NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `isRemote` tinyint(1) NOT NULL DEFAULT 0,
  `salaryMin` int(11) DEFAULT NULL,
  `salaryMax` int(11) DEFAULT NULL,
  `salaryCurrency` varchar(10) DEFAULT 'USD',
  `salaryPeriod` enum('hourly','daily','weekly','monthly','yearly') DEFAULT 'yearly',
  `isSalaryVisible` tinyint(1) NOT NULL DEFAULT 1,
  `applicationsCount` int(11) NOT NULL DEFAULT 0,
  `viewsCount` int(11) NOT NULL DEFAULT 0,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `isFeatured` tinyint(1) NOT NULL DEFAULT 0,
  `expiresAt` datetime NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`jobId`, `companyId`, `postedBy`, `jobTitle`, `jobSlug`, `jobDescription`, `jobPhoto`, `jobRequirements`, `jobBenefits`, `jobType`, `experienceLevel`, `location`, `isRemote`, `salaryMin`, `salaryMax`, `salaryCurrency`, `salaryPeriod`, `isSalaryVisible`, `applicationsCount`, `viewsCount`, `isActive`, `isFeatured`, `expiresAt`, `createdAt`, `updatedAt`) VALUES
(5, 11, 10, 'Senior PHP Developer', 'senior-php-developer', 'We are looking for an experienced PHP developer to join our team.', NULL, '5+ years PHP experience, Laravel framework, MySQL', 'Health insurance, flexible hours, remote work options', 'fullTime', 'senior', 'New York, NY', 1, 90000, 120000, 'USD', 'yearly', 1, 0, 0, 1, 1, '2025-07-19 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(6, 11, 10, 'Frontend React Developer', 'frontend-react-developer', 'Join our frontend team to build amazing user interfaces.', NULL, '3+ years React, JavaScript, HTML/CSS', 'Stock options, annual bonus, learning budget', 'fullTime', 'mid', 'San Francisco, CA', 0, 85000, 110000, 'USD', 'yearly', 1, 0, 0, 1, 0, '2025-07-20 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(7, 11, 10, 'DevOps Engineer', 'devops-engineer', 'Help us build and maintain our cloud infrastructure.', NULL, 'AWS, Docker, Kubernetes, CI/CD pipelines', 'Remote work, conference budget, premium hardware', 'fullTime', 'senior', NULL, 1, 100000, 140000, 'USD', 'yearly', 1, 0, 0, 1, 1, '2025-07-21 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(8, 11, 10, 'UX/UI Designer', 'ux-ui-designer', 'Design beautiful and intuitive interfaces for our products.', NULL, 'Portfolio required, Figma/Sketch, user research', 'Creative freedom, design team outings', 'fullTime', 'mid', 'Chicago, IL', 1, 75000, 95000, 'USD', 'yearly', 1, 0, 0, 1, 0, '2025-07-22 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(9, 11, 10, 'Data Scientist', 'data-scientist', 'Work with our data team to extract insights from large datasets.', NULL, 'Python, SQL, machine learning, statistics', 'Research opportunities, publication support', 'fullTime', 'senior', 'Boston, MA', 0, 110000, 150000, 'USD', 'yearly', 1, 0, 0, 1, 1, '2025-07-23 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(10, 11, 10, 'Technical Writer', 'technical-writer', 'Create documentation for our developer products.', NULL, 'Technical background, writing samples', 'Flexible schedule, work from anywhere', 'contract', 'mid', NULL, 1, 40, 60, 'USD', 'hourly', 1, 0, 0, 1, 0, '2025-07-24 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(11, 11, 10, 'Customer Support Specialist', 'customer-support-specialist', 'Help our customers with technical issues.', NULL, 'Communication skills, technical aptitude', 'Career growth opportunities, team events', 'fullTime', 'entry', 'Austin, TX', 0, 45000, 55000, 'USD', 'yearly', 1, 0, 0, 1, 0, '2025-07-25 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(12, 11, 10, 'Marketing Manager', 'marketing-manager', 'Lead our digital marketing efforts.', NULL, '5+ years marketing experience, analytics', 'Performance bonuses, creative control', 'fullTime', 'senior', 'Seattle, WA', 1, 80000, 100000, 'USD', 'yearly', 1, 0, 0, 1, 0, '2025-07-26 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(13, 11, 10, 'Mobile App Developer', 'mobile-app-developer', 'Build cross-platform mobile applications.', NULL, 'React Native, iOS/Android, JavaScript', 'Latest devices, app store credits', 'fullTime', 'mid', 'Denver, CO', 1, 85000, 115000, 'USD', 'yearly', 1, 0, 0, 1, 1, '2025-07-27 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(14, 11, 10, 'QA Engineer', 'qa-engineer', 'Ensure the quality of our software products.', NULL, 'Testing methodologies, automation tools', 'Quality-focused culture, professional development', 'fullTime', 'mid', 'Miami, FL', 0, 70000, 90000, 'USD', 'yearly', 1, 0, 0, 1, 0, '2025-07-28 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(15, 11, 10, 'Backend Node.js Developer', 'backend-nodejs-developer', 'Develop and maintain our server-side applications.', NULL, 'Node.js, Express, MongoDB, REST APIs', 'Tech stack freedom, open source contributions', 'fullTime', 'mid', 'Portland, OR', 1, 90000, 120000, 'USD', 'yearly', 1, 0, 0, 1, 0, '2025-07-29 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(16, 11, 10, 'Product Manager', 'product-manager', 'Lead product development from conception to launch.', NULL, 'Agile methodologies, market research', 'Stock options, leadership opportunities', 'fullTime', 'senior', 'Atlanta, GA', 0, 100000, 130000, 'USD', 'yearly', 1, 0, 0, 1, 1, '2025-07-30 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(17, 11, 10, 'Content Writer', 'content-writer', 'Create engaging content for our blog and website.', NULL, 'Writing samples, SEO knowledge', 'Flexible topics, byline opportunities', 'partTime', 'entry', NULL, 1, 30, 50, 'USD', 'hourly', 1, 0, 0, 1, 0, '2025-07-31 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(18, 11, 10, 'Systems Administrator', 'systems-administrator', 'Maintain and optimize our IT infrastructure.', NULL, 'Linux, networking, security best practices', 'On-call bonuses, certification support', 'fullTime', 'mid', 'Dallas, TX', 0, 75000, 95000, 'USD', 'yearly', 1, 0, 0, 1, 0, '2025-08-01 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(19, 11, 10, 'Sales Representative', 'sales-representative', 'Sell our software solutions to businesses.', NULL, 'Sales experience, communication skills', 'Uncapped commission, company trips', 'fullTime', 'entry', 'Phoenix, AZ', 1, 50000, 80000, 'USD', 'yearly', 1, 0, 0, 1, 0, '2025-08-02 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(20, 11, 10, 'Python Developer Intern', 'python-developer-intern', 'Learn Python development in a real-world setting.', NULL, 'Basic Python knowledge, eagerness to learn', 'Mentorship, potential full-time offer', 'internship', 'entry', 'Raleigh, NC', 0, 20, 25, 'USD', 'hourly', 1, 0, 0, 1, 0, '2025-08-03 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(21, 11, 10, 'Blockchain Developer', 'blockchain-developer', 'Work on our decentralized applications.', NULL, 'Solidity, Ethereum, smart contracts', 'Cutting-edge projects, crypto bonuses', 'contract', 'senior', NULL, 1, 100, 150, 'USD', 'hourly', 1, 0, 0, 1, 1, '2025-08-04 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(22, 11, 10, 'HR Coordinator', 'hr-coordinator', 'Support our growing team with HR functions.', NULL, 'HR experience, organizational skills', 'People-focused role, team events', 'fullTime', 'entry', 'Minneapolis, MN', 0, 50000, 60000, 'USD', 'yearly', 1, 0, 0, 1, 0, '2025-08-05 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(23, 11, 10, 'Graphic Designer', 'graphic-designer', 'Create visual content for marketing and products.', NULL, 'Adobe Creative Suite, portfolio required', 'Creative team, design freedom', 'freelance', 'mid', NULL, 1, 35, 60, 'USD', 'hourly', 1, 0, 0, 1, 0, '2025-08-06 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43'),
(24, 11, 10, 'Database Administrator', 'database-administrator', 'Optimize and maintain our database systems.', NULL, 'SQL, performance tuning, backup strategies', 'Stable systems, critical role', 'fullTime', 'senior', 'Salt Lake City, UT', 1, 95000, 125000, 'USD', 'yearly', 1, 0, 0, 1, 0, '2025-08-07 00:00:00', '2025-05-19 00:00:00', '2025-05-19 05:44:43');

-- --------------------------------------------------------

--
-- Table structure for table `jobskills`
--

CREATE TABLE `jobskills` (
  `jobSkillId` int(11) NOT NULL,
  `jobId` int(11) NOT NULL,
  `skillName` varchar(100) NOT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `savedjobs`
--

CREATE TABLE `savedjobs` (
  `savedJobId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `jobId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `userType` enum('jobSeeker','employer','admin') NOT NULL DEFAULT 'jobSeeker',
  `phoneNumber` varchar(20) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profilePhoto` varchar(255) DEFAULT NULL,
  `isEmailVerified` tinyint(1) NOT NULL DEFAULT 0,
  `websiteUrl` varchar(255) DEFAULT NULL,
  `linkedinUrl` varchar(255) DEFAULT NULL,
  `githubUrl` varchar(255) DEFAULT NULL,
  `verificationToken` varchar(100) DEFAULT NULL,
  `verificationTokenExpires` datetime DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `lastLogin` datetime DEFAULT NULL,
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `email`, `passwordHash`, `firstName`, `lastName`, `userType`, `phoneNumber`, `location`, `bio`, `profilePhoto`, `isEmailVerified`, `websiteUrl`, `linkedinUrl`, `githubUrl`, `verificationToken`, `verificationTokenExpires`, `createdAt`, `lastLogin`, `updatedAt`) VALUES
(1, 'admin@jobest.com', '$2y$10$92IOTtMNDSXiRqsHevj/muh8sJyx3.HKtAbHmqHRW/lzD7C3j.Tv.', 'Admin', 'User', 'admin', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-18 23:51:12', NULL, '2025-05-18 22:51:12'),
(3, 'admin1@jobest.com', '$2y$10$92IOTtMNDSXiRqsHevj/muh8sJyx3.HKtAbHmqHRW/lzD7C3j.Tv.', 'Admin', 'User', 'admin', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-18 23:51:54', NULL, '2025-05-18 22:51:54'),
(10, 'oussamabaidi10@gmail.com', '$2y$12$raLx/2/3I18Lz9TDkCx/jOGiXWzpO8NE4p2RQHCFzWpF9wtDnKUou', 'oussama', 'baidi', 'employer', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 'b1481b9a8196212a6bb9a85ebb870d09', '2025-05-20 00:28:38', '2025-05-19 00:28:38', '2025-05-19 06:18:44', '2025-05-19 05:18:44'),
(12, 'ayoubeddy@gmail.com', '$2y$12$JYrmCBRNYR5ijQE7LZXBoetIOPF67xrg/82O4/lwojbpCKEaHFDf.', 'oussama', 'baidi', 'employer', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '05d212efa0213d9b4ebf7d38fc10b9b9', '2025-05-20 00:31:25', '2025-05-19 00:31:25', NULL, '2025-05-18 23:44:05'),
(13, 'test@example.com', '/muh8sJyx3.HKtAbHmqHRW/lzD7C3j.Tv.', 'Test', 'User', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 00:44:41', NULL, '2025-05-18 23:44:41'),
(14, 'alice@example.com', '$2y$10$92IOTtMNDSXiRqsHevj/muh8sJyx3.HKtAbHmqHRW/lzD7C3j.Tv.', 'Alice', 'Johnson', 'jobSeeker', '555-0101', 'New York, NY', 'Experienced web developer with 5+ years in PHP and JavaScript', NULL, 1, 'https://alice.dev', 'https://linkedin.com/in/alice', 'https://github.com/alice', NULL, NULL, '2025-05-19 01:16:10', NULL, '2025-05-19 00:16:10'),
(15, 'bob@example.com', '$2y$10$92IOTtMNDSXiRqsHevj/muh8sJyx3.HKtAbHmqHRW/lzD7C3j.Tv.', 'Bob', 'Smith', 'jobSeeker', '555-0102', 'San Francisco, CA', 'Frontend specialist with React expertise', NULL, 1, 'https://bob.dev', 'https://linkedin.com/in/bob', 'https://github.com/bob', NULL, NULL, '2025-05-19 01:16:10', NULL, '2025-05-19 00:16:10'),
(16, 'charlie@example.com', '$2y$10$92IOTtMNDSXiRqsHevj/muh8sJyx3.HKtAbHmqHRW/lzD7C3j.Tv.', 'Charlie', 'Brown', 'jobSeeker', '555-0103', 'Chicago, IL', 'UX/UI designer with a focus on accessibility', NULL, 1, 'https://charlie.design', 'https://linkedin.com/in/charlie', 'https://github.com/charlie', NULL, NULL, '2025-05-19 01:16:10', NULL, '2025-05-19 00:16:10'),
(17, 'diana@example.com', '$2y$10$92IOTtMNDSXiRqsHevj/muh8sJyx3.HKtAbHmqHRW/lzD7C3j.Tv.', 'Diana', 'Prince', 'employer', '555-0104', 'Boston, MA', 'HR Manager at TechSolutions', NULL, 1, 'https://techsolutions.com', 'https://linkedin.com/in/diana', NULL, NULL, NULL, '2025-05-19 01:16:10', NULL, '2025-05-19 00:16:10'),
(18, 'edward@example.com', '$2y$10$92IOTtMNDSXiRqsHevj/muh8sJyx3.HKtAbHmqHRW/lzD7C3j.Tv.', 'Edward', 'Norton', 'employer', '555-0105', 'Austin, TX', 'CTO at DataSystems', NULL, 1, 'https://datasystems.io', 'https://linkedin.com/in/edward', NULL, NULL, NULL, '2025-05-19 01:16:10', NULL, '2025-05-19 00:16:10'),
(19, 'fiona@example.com', '$2y$10$92IOTtMNDSXiRqsHevj/muh8sJyx3.HKtAbHmqHRW/lzD7C3j.Tv.', 'Fiona', 'Green', 'employer', '555-0106', 'Seattle, WA', 'Recruiter at CloudTech', NULL, 1, 'https://cloudtech.com', 'https://linkedin.com/in/fiona', NULL, NULL, NULL, '2025-05-19 01:16:10', NULL, '2025-05-19 00:16:10'),
(20, 'user1@example.com', 'hash1', 'John', 'Doe', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(21, 'user2@example.com', 'hash2', 'Jane', 'Smith', 'employer', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(22, 'user3@example.com', 'hash3', 'Ali', 'Hassan', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(23, 'user4@example.com', 'hash4', 'Sara', 'Jones', 'employer', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(24, 'user5@example.com', 'hash5', 'Emily', 'Stone', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(25, 'user6@example.com', 'hash6', 'Mohamed', 'Ali', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(26, 'user7@example.com', 'hash7', 'Linda', 'Green', 'employer', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(27, 'user8@example.com', 'hash8', 'Tariq', 'Saleh', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(28, 'user9@example.com', 'hash9', 'Amina', 'Bouchra', 'employer', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(29, 'user10@example.com', 'hash10', 'Ziad', 'Kamal', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(30, 'user11@example.com', 'hash11', 'Ali', 'Sami', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(31, 'user12@example.com', 'hash12', 'Nour', 'Zahra', 'employer', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(32, 'user13@example.com', 'hash13', 'Nabil', 'Ahmed', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(33, 'user14@example.com', 'hash14', 'Fatima', 'Omar', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(34, 'user15@example.com', 'hash15', 'Rachid', 'Yassine', 'employer', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(35, 'user16@example.com', 'hash16', 'Meryem', 'Laila', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(36, 'user17@example.com', 'hash17', 'Said', 'Mounir', 'employer', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(37, 'user18@example.com', 'hash18', 'Karim', 'Nadia', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(38, 'user19@example.com', 'hash19', 'Youssef', 'Imane', 'employer', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23'),
(39, 'user20@example.com', 'hash20', 'Hicham', 'Layla', 'jobSeeker', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-05-19 06:30:23', NULL, '2025-05-19 05:30:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`applicationId`),
  ADD KEY `jobId` (`jobId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`companyId`),
  ADD UNIQUE KEY `companySlug` (`companySlug`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `cronlogs`
--
ALTER TABLE `cronlogs`
  ADD PRIMARY KEY (`logId`);

--
-- Indexes for table `dailystats`
--
ALTER TABLE `dailystats`
  ADD PRIMARY KEY (`statId`),
  ADD UNIQUE KEY `date` (`date`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`jobId`),
  ADD UNIQUE KEY `jobSlug` (`jobSlug`),
  ADD KEY `companyId` (`companyId`),
  ADD KEY `postedBy` (`postedBy`);

--
-- Indexes for table `jobskills`
--
ALTER TABLE `jobskills`
  ADD PRIMARY KEY (`jobSkillId`),
  ADD KEY `jobId` (`jobId`);

--
-- Indexes for table `savedjobs`
--
ALTER TABLE `savedjobs`
  ADD PRIMARY KEY (`savedJobId`),
  ADD UNIQUE KEY `unique_saved_job` (`userId`,`jobId`),
  ADD KEY `jobId` (`jobId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `applicationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `companyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `cronlogs`
--
ALTER TABLE `cronlogs`
  MODIFY `logId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dailystats`
--
ALTER TABLE `dailystats`
  MODIFY `statId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `jobId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `jobskills`
--
ALTER TABLE `jobskills`
  MODIFY `jobSkillId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `savedjobs`
--
ALTER TABLE `savedjobs`
  MODIFY `savedJobId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`jobId`) REFERENCES `jobs` (`jobId`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`companyId`) REFERENCES `companies` (`companyId`) ON DELETE CASCADE,
  ADD CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`postedBy`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `jobskills`
--
ALTER TABLE `jobskills`
  ADD CONSTRAINT `jobskills_ibfk_1` FOREIGN KEY (`jobId`) REFERENCES `jobs` (`jobId`) ON DELETE CASCADE;

--
-- Constraints for table `savedjobs`
--
ALTER TABLE `savedjobs`
  ADD CONSTRAINT `savedjobs_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `savedjobs_ibfk_2` FOREIGN KEY (`jobId`) REFERENCES `jobs` (`jobId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
