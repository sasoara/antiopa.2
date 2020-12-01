--
-- Database `antiopa.2`
--
CREATE DATABASE IF NOT EXISTS `antiopa.2`;
USE `antiopa.2`;

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL,
    `pwd` VARCHAR(255) NOT NULL
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
-- Table structure for table `tags`
--
CREATE TABLE `tags` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL
)  ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8MB4;

--
-- Table structure for table `posts_has_tags`
--
CREATE TABLE `posts_has_tags` (
    `posts_id` INT NOT NULL,
    `tags_id` INT NOT NULL,
    PRIMARY KEY (`posts_id` , `tags_id`),
    FOREIGN KEY (`posts_id`)
        REFERENCES `posts` (`id`),
    FOREIGN KEY (`tags_id`)
        REFERENCES `tags` (`id`)
)  ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8MB4;

--
-- Show database tables `antiopa.2`
--
-- show tables;