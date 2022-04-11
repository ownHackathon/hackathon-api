-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 11. Apr 2022 um 19:40
-- Server-Version: 10.5.15-MariaDB-1:10.5.15+maria~focal-log
-- PHP-Version: 7.4.28

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `d009561a`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Comment`
--

CREATE TABLE `Comment` (
  `id` int(10) UNSIGNED NOT NULL,
  `eventId` int(10) UNSIGNED NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `Comment` varchar(8192) NOT NULL,
  `createTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Event`
--

CREATE TABLE `Event` (
  `id` int(10) UNSIGNED NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `eventText` varchar(8192) NOT NULL,
  `createTime` datetime NOT NULL DEFAULT current_timestamp(),
  `startTime` datetime NOT NULL,
  `duration` smallint(5) UNSIGNED NOT NULL DEFAULT 7,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `ratingCompleted` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `EventRating`
--

CREATE TABLE `EventRating` (
  `id` int(10) UNSIGNED NOT NULL,
  `minPoints` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `maxPoints` int(10) UNSIGNED NOT NULL DEFAULT 6
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `EventRatingCategory`
--

CREATE TABLE `EventRatingCategory` (
  `id` int(10) UNSIGNED NOT NULL,
  `eventId` int(10) UNSIGNED NOT NULL,
  `ratingCategoryId` int(10) UNSIGNED NOT NULL,
  `weighting` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Participant`
--

CREATE TABLE `Participant` (
  `id` int(10) UNSIGNED NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `eventId` int(10) UNSIGNED NOT NULL,
  `requestTime` datetime NOT NULL DEFAULT current_timestamp(),
  `approved` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `disqualified` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Project`
--

CREATE TABLE `Project` (
  `id` int(10) UNSIGNED NOT NULL,
  `participantId` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(8192) NOT NULL,
  `gitRepoUri` varchar(2048) NOT NULL,
  `demoPageUri` varchar(2048) DEFAULT NULL,
  `createTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ProjectBlog`
--

CREATE TABLE `ProjectBlog` (
  `id` int(10) UNSIGNED NOT NULL,
  `projectId` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `blogText` varchar(8192) NOT NULL,
  `createTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Rating`
--

CREATE TABLE `Rating` (
  `id` int(10) UNSIGNED NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `projectId` int(10) UNSIGNED NOT NULL,
  `eventRatingId` int(10) UNSIGNED NOT NULL,
  `eventRatingCategoryId` int(10) UNSIGNED NOT NULL,
  `rating` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `RatingCategory`
--

CREATE TABLE `RatingCategory` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Role`
--

CREATE TABLE `Role` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `TopicPool`
--

CREATE TABLE `TopicPool` (
  `id` int(10) UNSIGNED NOT NULL,
  `eventId` int(10) UNSIGNED DEFAULT NULL,
  `topic` varchar(1024) NOT NULL,
  `description` varchar(8096) DEFAULT NULL,
  `accepted` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `User`
--

CREATE TABLE `User` (
  `id` int(10) UNSIGNED NOT NULL,
  `roleId` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `name` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` varchar(300) DEFAULT NULL,
  `registrationTime` datetime NOT NULL DEFAULT current_timestamp(),
  `lastAction` datetime DEFAULT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Comment`
--
ALTER TABLE `Comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Comment_Event_idx` (`eventId`),
  ADD KEY `fk_Comment_User_idx` (`userId`);

--
-- Indizes für die Tabelle `Event`
--
ALTER TABLE `Event`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`),
  ADD KEY `fk_Event_User_idx` (`userId`);

--
-- Indizes für die Tabelle `EventRating`
--
ALTER TABLE `EventRating`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `EventRatingCategory`
--
ALTER TABLE `EventRatingCategory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_EventRatingCategory_Event_idx` (`eventId`),
  ADD KEY `fk_EventRatingCategory_RatingCategory_idx` (`ratingCategoryId`);

--
-- Indizes für die Tabelle `Participant`
--
ALTER TABLE `Participant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Participant_User_idx` (`userId`),
  ADD KEY `fk_Participant_Event_idx` (`eventId`);

--
-- Indizes für die Tabelle `Project`
--
ALTER TABLE `Project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Project_Participant_idx` (`participantId`);

--
-- Indizes für die Tabelle `ProjectBlog`
--
ALTER TABLE `ProjectBlog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ProjectBlog_Project_idx` (`projectId`);

--
-- Indizes für die Tabelle `Rating`
--
ALTER TABLE `Rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Rating_User_idx` (`userId`),
  ADD KEY `fk_Rating_Project_idx` (`projectId`),
  ADD KEY `fk_Rating_EventRating_idx` (`eventRatingId`),
  ADD KEY `fk_Rating_EventRatingCategory_idx` (`eventRatingCategoryId`);

--
-- Indizes für die Tabelle `RatingCategory`
--
ALTER TABLE `RatingCategory`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `Role`
--
ALTER TABLE `Role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_UNIQUE` (`name`);

--
-- Indizes für die Tabelle `TopicPool`
--
ALTER TABLE `TopicPool`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_TopicPool_Event_idx` (`eventId`),
  ADD KEY `fk_TopicPool_accepted_idx` (`accepted`);

--
-- Indizes für die Tabelle `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `fk_User_Role_idx` (`roleId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `Comment`
--
ALTER TABLE `Comment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `Event`
--
ALTER TABLE `Event`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `EventRating`
--
ALTER TABLE `EventRating`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `EventRatingCategory`
--
ALTER TABLE `EventRatingCategory`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `Participant`
--
ALTER TABLE `Participant`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `Project`
--
ALTER TABLE `Project`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `ProjectBlog`
--
ALTER TABLE `ProjectBlog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `Rating`
--
ALTER TABLE `Rating`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `RatingCategory`
--
ALTER TABLE `RatingCategory`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `Role`
--
ALTER TABLE `Role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `TopicPool`
--
ALTER TABLE `TopicPool`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `User`
--
ALTER TABLE `User`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `Comment`
--
ALTER TABLE `Comment`
  ADD CONSTRAINT `fk_Comment_Event` FOREIGN KEY (`eventId`) REFERENCES `Event` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Comment_User` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints der Tabelle `Event`
--
ALTER TABLE `Event`
  ADD CONSTRAINT `fk_Event_User` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `EventRatingCategory`
--
ALTER TABLE `EventRatingCategory`
  ADD CONSTRAINT `fk_EventRatingCategory_Event` FOREIGN KEY (`eventId`) REFERENCES `Event` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_EventRatingCategory_RatingCategory` FOREIGN KEY (`ratingCategoryId`) REFERENCES `RatingCategory` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints der Tabelle `Participant`
--
ALTER TABLE `Participant`
  ADD CONSTRAINT `fk_Participant_Event` FOREIGN KEY (`eventId`) REFERENCES `Event` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Participant_User` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints der Tabelle `Project`
--
ALTER TABLE `Project`
  ADD CONSTRAINT `fk_Project_Participant` FOREIGN KEY (`participantId`) REFERENCES `Participant` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ProjectBlog`
--
ALTER TABLE `ProjectBlog`
  ADD CONSTRAINT `fk_ProjectBlog_Project` FOREIGN KEY (`projectId`) REFERENCES `Project` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints der Tabelle `Rating`
--
ALTER TABLE `Rating`
  ADD CONSTRAINT `fk_Rating_EventRating` FOREIGN KEY (`eventRatingId`) REFERENCES `EventRating` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Rating_EventRatingCategory` FOREIGN KEY (`eventRatingCategoryId`) REFERENCES `EventRatingCategory` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Rating_Project` FOREIGN KEY (`projectId`) REFERENCES `Project` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Rating_User` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints der Tabelle `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `fk_User_Role` FOREIGN KEY (`roleId`) REFERENCES `Role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
