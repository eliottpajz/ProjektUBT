CREATE DATABASE IF NOT EXISTS `bankaproject` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bankaproject`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user','admin') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(80) NOT NULL UNIQUE,
  `title` VARCHAR(150) NOT NULL,
  `content` TEXT DEFAULT NULL,
  `extras` JSON DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `products` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `news` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `content` TEXT NOT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `author_id` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `news_author_fk` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pages` (`slug`, `title`, `content`, `extras`) VALUES
('home', 'Banking made simple.', NULL, JSON_OBJECT(
  'hero_title', 'Banking made simple.',
  'hero_text', 'Open an account in minutes, manage your money 24/7, and reach your goals faster.',
  'feature_title', 'What am I looking for?',
  'features', JSON_ARRAY(
    'I want to apply for a credit card',
    'I want to take out a loan over 10,000 €',
    'I want to take out a loan over 30,000 €',
    'I want to see the exchange rates of the day',
    'I want to update my info'
  )
)),
('about', 'Find out more about:', NULL, JSON_OBJECT(
  'boxes', JSON_ARRAY(
    'Management Board',
    'Prices & Evaluations',
    'Financial Statements',
    'Code of Ethics',
    'Board of Directors',
    'ESG Financing',
    'Annual Reports',
    'FATCA',
    'Mission and Vision',
    'Branch Network',
    'Community Investments',
    'Personal Data Protection'
  )
));

INSERT INTO `products` (`title`, `description`, `image_url`) VALUES
('Cards', 'Debit and credit cards with no monthly fees and rewards on every purchase.', NULL),
('Loans', 'Personal and home loans with flexible terms and competitive interest rates.', NULL),
('Savings', 'High-interest savings accounts to help you grow your money safely.', NULL);

INSERT INTO `news` (`title`, `content`, `image_url`) VALUES
('Bank launches digital savings tool', 'Our new savings app helps customers set goals and track progress automatically.', NULL),
('Low-rate home loans now available', 'Discover our new mortgage packages for first-time buyers and existing homeowners.', NULL);
