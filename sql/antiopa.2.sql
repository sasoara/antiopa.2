-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Erstellungszeit: 30. Nov 2020 um 13:03
-- Server-Version: 5.7.26
-- PHP-Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `antiopa.2`
--
CREATE DATABASE IF NOT EXISTS `antiopa.2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `antiopa.2`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `date` date NOT NULL,
  `content_type` varchar(255) NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `users_id` int(11) NOT NULL,
  `secure_file_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `posts_has_tags`
--

CREATE TABLE `posts_has_tags` (
  `posts_id` int(11) NOT NULL,
  `tags_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`);

--
-- Indizes für die Tabelle `posts_has_tags`
--
ALTER TABLE `posts_has_tags`
  ADD PRIMARY KEY (`posts_id`,`tags_id`),
  ADD KEY `tags_id` (`tags_id`);

--
-- Indizes für die Tabelle `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT für Tabelle `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Constraints der Tabelle `posts_has_tags`
--
ALTER TABLE `posts_has_tags`
  ADD CONSTRAINT `posts_has_tags_ibfk_1` FOREIGN KEY (`posts_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `posts_has_tags_ibfk_2` FOREIGN KEY (`tags_id`) REFERENCES `tags` (`id`);
