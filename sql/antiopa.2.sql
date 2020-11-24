-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema antiopa.2
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema antiopa.2
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `antiopa.2` DEFAULT CHARACTER SET utf8mb4 ;
USE `antiopa.2` ;

-- -----------------------------------------------------
-- Table `antiopa.2`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `antiopa.2`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `pwd` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 14
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `antiopa.2`.`posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `antiopa.2`.`posts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `date` DATE NOT NULL,
  `content_type` VARCHAR(255) NOT NULL,
  `is_public` TINYINT(1) NOT NULL DEFAULT '0',
  `created_on` DATETIME NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `users_id` INT NOT NULL,
  `secure_file_name` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `users_id` (`users_id` ASC) VISIBLE,
  CONSTRAINT `posts_ibfk_1`
    FOREIGN KEY (`users_id`)
    REFERENCES `antiopa.2`.`users` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 76
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `antiopa.2`.`tags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `antiopa.2`.`tags` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 173
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `antiopa.2`.`posts_has_tags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `antiopa.2`.`posts_has_tags` (
  `posts_id` INT NOT NULL,
  `tags_id` INT NOT NULL,
  PRIMARY KEY (`posts_id`, `tags_id`),
  INDEX `tags_id` (`tags_id` ASC) VISIBLE,
  CONSTRAINT `posts_has_tags_ibfk_1`
    FOREIGN KEY (`posts_id`)
    REFERENCES `antiopa.2`.`posts` (`id`),
  CONSTRAINT `posts_has_tags_ibfk_2`
    FOREIGN KEY (`tags_id`)
    REFERENCES `antiopa.2`.`tags` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
