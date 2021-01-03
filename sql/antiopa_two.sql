--
-- Database `antiopa_two`
--
CREATE DATABASE IF NOT EXISTS `antiopa_two`;
USE `antiopa_two`;

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL,
    `pwd` VARCHAR(255) NOT NULL,
    `ur` varchar(255)
)  ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8MB4;

--
-- Table structure for table `posts`
--
CREATE TABLE `posts` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT(1000),
    `content_type` VARCHAR(255) NOT NULL,
    `is_public` BOOLEAN NOT NULL DEFAULT 0,
    `created_on` DATETIME NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `users_id` INT NOT NULL,
    `secure_file_name` VARCHAR(255) NOT NULL,
    `date` DATE NOT NULL,
    FOREIGN KEY (`users_id`)
        REFERENCES `users` (`id`)
)  ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8MB4;

--
-- Show database tables `antiopa_two`
--
-- show tables;