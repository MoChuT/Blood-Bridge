CREATE DATABASE IF NOT EXISTS `blood_bridge`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `blood_bridge`;

CREATE TABLE IF NOT EXISTS `donors` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(160) NOT NULL,
  `blood_type` VARCHAR(5) DEFAULT NULL,
  `phone` VARCHAR(40) DEFAULT NULL,
  `email` VARCHAR(180) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `date_of_birth` DATE DEFAULT NULL,
  `emergency_contact` VARCHAR(160) DEFAULT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `status` VARCHAR(80) NOT NULL DEFAULT 'Pending verification',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_donors_email` (`email`),
  UNIQUE KEY `idx_donors_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `health_records` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `donor_key` VARCHAR(180) DEFAULT NULL,
  `answers` JSON DEFAULT NULL,
  `eligibility_status` VARCHAR(80) DEFAULT NULL,
  `result` VARCHAR(160) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_health_records_donor_key` (`donor_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `profile_updates` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(160) DEFAULT NULL,
  `blood_type` VARCHAR(5) DEFAULT NULL,
  `phone` VARCHAR(40) DEFAULT NULL,
  `email` VARCHAR(180) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `emergency_contact` VARCHAR(160) DEFAULT NULL,
  `status` VARCHAR(80) NOT NULL DEFAULT 'Pending admin review',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `documents` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_type` VARCHAR(120) DEFAULT NULL,
  `upload_date` DATE DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `file_name` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `appointments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `preferred_date` DATE DEFAULT NULL,
  `preferred_time` TIME DEFAULT NULL,
  `venue` VARCHAR(180) DEFAULT NULL,
  `status` VARCHAR(80) NOT NULL DEFAULT 'Pending approval',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `inventory_updates` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `blood_type` VARCHAR(5) DEFAULT NULL,
  `quantity_update` INT DEFAULT NULL,
  `last_updated` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `appointment_decisions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `appointment` VARCHAR(180) DEFAULT NULL,
  `decision` VARCHAR(80) DEFAULT NULL,
  `reviewed_by` VARCHAR(120) DEFAULT NULL,
  `remarks` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(180) NOT NULL,
  `status` VARCHAR(80) DEFAULT NULL,
  `event_date` DATE DEFAULT NULL,
  `details` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `donation_records` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `donor_name` VARCHAR(160) DEFAULT NULL,
  `blood_type` VARCHAR(5) DEFAULT NULL,
  `donation_date` DATE DEFAULT NULL,
  `result` VARCHAR(80) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `matching_alerts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `blood_type` VARCHAR(5) DEFAULT NULL,
  `radius` VARCHAR(40) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `announcements` (`title`, `status`, `event_date`, `details`)
SELECT 'MMU Blood Donation Drive', 'Published', '2026-05-12', 'Main Hall, May 12-13, 2026. Bring identification and eat before donating.'
WHERE NOT EXISTS (SELECT 1 FROM `announcements`);

INSERT INTO `announcements` (`title`, `status`, `event_date`, `details`)
SELECT 'O- Donor Request', 'Priority', '2026-05-13', 'O- stock is low. Eligible O- donors are encouraged to book early slots.'
WHERE (SELECT COUNT(*) FROM `announcements`) = 1;

INSERT INTO `announcements` (`title`, `status`, `event_date`, `details`)
SELECT 'Health Screening Reminder', 'Notice', '2026-05-14', 'Complete screening before appointment confirmation.'
WHERE (SELECT COUNT(*) FROM `announcements`) = 2;
