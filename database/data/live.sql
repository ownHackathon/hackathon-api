-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 11. Apr 2022 um 00:17
-- Server-Version: 10.5.15-MariaDB-1:10.5.15+maria~focal-log
-- PHP-Version: 7.4.28

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

--
-- Daten für Tabelle `Event`
--

INSERT INTO `Event` (`id`, `userId`, `name`, `description`, `eventText`, `createTime`, `startTime`, `duration`, `status`, `ratingCompleted`) VALUES
(1, 1, '*Y21-C01*', 'Erstes Event - noch bekannt unter JAM', 'Willkommen zu dem Ersten Web-Application Jam auf dem Discord-Server von BlackScorp.\r\n\r\nBekanntgabe des Themas: Freitag den 20.08.2021 - ca. 18 Uhr\r\n\r\nZeitansatz bis Abgabe: 14 Tage (Stichzeit 03.09.2021 - 18 Uhr\r\n\r\nFür alle unentschlossenen, zu gewinnen gibt es folgendes:\r\n\r\n1. Platz:\r\nDer pragmatische Programmierer: Ihr Weg zur Meisterschaft Gebundene Ausgabe – 9. April 2021\r\nhttps://i.imgur.com/mI3dBHZ.jpg\r\n\r\n2. Platz: 15€ Amazon Gutschein\r\n3. Platz: 10€ Amazon Gutschein \r\n', '2021-08-09 22:58:02', '2021-08-20 18:00:00', 14, 5, 0),
(2, 1, '*Y21-C02*', 'Zweiter Durchgang - nun bekannt als Hackathon', 'Da der erste Durchlauf soviel Spaß gemacht hat, zumindest mir, plane ich ein neues Event.\r\n\r\nBekanntgabe des Themas: Freitag den 12.11.2021 - ca. 18 Uhr\r\n\r\nZeitansatz bis Abgabe: 7 Tage (Stichzeit 19.11.2021 - 18 Uhr\r\n\r\nWie beim vorherigen Durchlauf bitte die Teilnahmeanmeldung per ? Reaktion auf diesen Beitrag.\r\nFür die Abmeldung bitte die Reaktion wieder entfernen.', '2021-09-12 23:08:38', '2021-11-12 18:00:00', 7, 5, 1);

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

--
-- Daten für Tabelle `Participant`
--

INSERT INTO `Participant` (`id`, `userId`, `eventId`, `requestTime`, `approved`, `disqualified`) VALUES
(1, 5, 1, '2022-04-10 23:31:27', 1, 0),
(2, 9, 1, '2022-04-10 23:31:27', 1, 0),
(3, 6, 1, '2022-04-10 23:31:27', 1, 0),
(4, 2, 1, '2022-04-10 23:31:27', 1, 0),
(5, 7, 1, '2022-04-10 23:31:27', 1, 0),
(6, 11, 1, '2022-04-10 23:31:27', 1, 0),
(7, 1, 2, '2022-04-10 23:34:40', 1, 0),
(8, 2, 2, '2022-04-10 23:34:40', 1, 0),
(9, 3, 2, '2022-04-10 23:34:40', 1, 0),
(10, 6, 2, '2022-04-10 23:34:40', 1, 0),
(11, 5, 2, '2022-04-10 23:34:40', 1, 0),
(12, 7, 2, '2022-04-10 23:34:40', 1, 0);

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

--
-- Daten für Tabelle `Project`
--

INSERT INTO `Project` (`id`, `participantId`, `title`, `description`, `gitRepoUri`, `demoPageUri`, `createTime`) VALUES
(1, 5, 'Restaurant Bansai Single-Form-Application', 'This project was created in the framework of PHP JAM on the BlackScorp Discord Server.\r\n\r\nHere you can see a reservation form where you have to fill in all fields except for notes in order to send a reservation. If you leave a real email, you will also get a confirmation email. There you will find all the important data for your visit and a link with which you can cancel your reservation. All your data will be deleted from the DB, and you can rest assured. Parts of the code were programmed OOP and some functions were also oriented. I also switched off the error reporting so that it doesn\'t get smacked in the face of the visitors ...\r\n\r\nI hope you like my project:)\r\n\r\nAC4G', 'https://github.com/AC4G/Single-Form-Application-', NULL, '2021-08-31 20:28:00'),
(2, 1, 'ProFinal', 'ProFinal\r\nProFinal is my project for the 1st Jam on BlackScorp\'s Discord server. The requirements allowed me to use only one form.\r\nProFinal stands for \"progressive finalisation\".\r\n\r\nWhat is ProFinal?\r\nProFinal helps you to check if you have planned everything for your website. It tries to give you small tips on what\r\nelse you might need for the perfect website. ProFinal analyzes the given URL and the source code behind it.\r\n\r\nTask\r\nProFinal checks various areas of tasks.\r\n\r\nGeneral\r\n\r\nDeprecated HTML Tags\r\nIt searches for HTML tags that are deprecated in HTML 5.\r\n\r\nFavicon\r\nSearches for a favicon in the source code and in the uri /favicon.ico\r\n\r\nSEO\r\nSearches for the robots.txt and the included sitemap reference\r\n\r\nMetadata\r\nSearch for title, description, keywords and theme color.\r\n\r\nSecurity\r\nChecks a list of bad files pattern, located in config/packages/pro_final.yaml\r\nand check also the ssl certificate.', 'https://gitlab.com/22h/pro-final/-/tree/1.0.0', 'https://profinal.22h.de/', '2021-08-20 18:23:22'),
(3, 4, 'Shareplications', 'Shareplications\r\n\r\nShare Applications\r\nWhat is this?\r\n\r\nThis is my First Project for the PHP Coding Jam for the Discord of the YouTuber \"Vitalij Mik\" Shareplications gives you a URL-Shortener with an option to generate QR-Codes directly after creating the shortcode.\r\nRequirements\r\n\r\n    Composer for setting up the QR-Code Generation Library 2amigos/qrcode-library\r\n    PHP 8.0 or higher\r\n    A MariaDB / MySQL Database\r\n\r\nSetup\r\n\r\n    Import the database.sql into your Database\r\n    Set your Database Credentials in the config/database.php File\r\n    Setup composer by using composer update (or extract the vendor.zip File)\r\n    Access your Page and start creating Short-URLs\r\n', 'https://github.com/namePlayer/shareplications', 'https://shareplications.npcore.net/', '2021-08-20 18:25:59'),
(4, 2, 'Zippify', 'This tool allows you to download a generated zip/tar from multiple files.', 'https://github.com/xatenev/zippify', 'http://zippify.xatenev.com/', '2021-08-21 14:44:31'),
(5, 6, 'Single-Form-Application', '', 'https://github.com/schmaiki/Single-Form-Application', NULL, '2021-08-23 07:30:38'),
(6, 3, 'Numbers', 'Find facts about numbers.\r\n\r\nCreated for the BlackScorp Discord Web-Application Jam Y21-C01. The task is to create a single form application.', 'https://github.com/steltner/numbers', 'http://numbers.steltner.net/', '2021-09-02 04:50:48'),
(7, 7, 'Secret Paper', 'Secret Paper - Hackathon Project at Nov 2021 - Topic \"Secret Sharing\"\r\n\r\nMy project is about sharing secret papers. Both plain text or pdf files can be uploaded. Optionally, a password can be stored for access.', 'https://github.com/BibaltiK/secret-paper', 'https://secret-paper.exdrals.de/', '2021-11-12 20:10:43'),
(8, 11, 'søppel', 'pase code and share it!\r\n\r\nIntroduction\r\nSøppel is a simplified service for sharing code examples or other texts.', 'https://gitlab.com/22h/soppel/-/tree/hackathon-y12-c02', 'https://soppel.22h.de/', '2021-11-12 21:00:46'),
(9, 8, 'TextyShare', 'With this Service you can share Texts in Form of Markdown anonymously. After saving the text will be saved into a JSON Document, which cannot be edited anymore.\r\n\r\nThis Project was intentionally coded for the Second Hackathon (formerly named PHP Jam) of the BlackScorp Discord Server.', 'https://github.com/namePlayer/TextyShare/tree/v1.0.0', 'http://textyshare.npcore.net/', '2021-11-13 11:56:34'),
(10, 9, 'share-a-secret', 'Black Hackathon Y21-C02 Project -Geheimes Teilen-', 'https://github.com/sklaus86/share-a-secret', NULL, '2021-11-14 19:21:27'),
(11, 12, 'Upload-Systems-Cloudi', '', 'https://github.com/AC4G/Upload-Systems-Cloudi', NULL, '2021-11-14 19:32:20');

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

--
-- Daten für Tabelle `Role`
--

INSERT INTO `Role` (`id`, `name`, `description`) VALUES
(1, 'Administrator', NULL),
(2, 'Moderator', NULL),
(3, 'User', NULL);

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

--
-- Daten für Tabelle `TopicPool`
--

INSERT INTO `TopicPool` (`id`, `eventId`, `topic`, `description`, `accepted`) VALUES
(1, 1, 'Single Form Application', '', 1),
(3, NULL, 'Transaction Service', 'Wie Paypal', 1),
(4, NULL, 'Random Generator', 'Entwickel eine Anwendung, welche dem Nutzer zufällige Daten ausgibt. Was genau ist dir selbst überlassen, die einzige Bedingung ist, der Nutzer muss dafür nur einen Button drücken oder die Seite aufrufen. Nutzereingaben sind nicht gestattet.', 1),
(5, NULL, 'Statistik Dashboard', 'Entwickel eine Seite auf welcher Statistiken angezeigt werden. Was für Statistiken es sind, ist jedem selbst überlassen, wichtig ist nur das diese über externe Schnittstellen gezogen werden. Um so mehr um so besser. Die Seite soll öffentlich verfügbar sein und nicht hinter einer Firewall versteckt sein.', 1),
(6, NULL, 'Minigame', 'Entwickel ein Minigame im Browser. Dabei ist es egal ob dafür Javascript zum Einsatz kommt oder nicht. Einzige Bedingung ist, es muss ohne großes Regelwerk verstanden werden können.', 1),
(7, NULL, 'Kurz aber gut', 'Entwickel eine Webanwendung mit maximal 1.000 Zeilen PHP Code auf PSR-12 Standard. HTML und CSS sind dabei nicht beschränkt. JS ist wiederum nicht erlaubt. Es zählen nur die reinen Codezeilen, das heißt Leerzeilen und Kommentare werden nicht gezählt. Tests sind von den 1.000 Zeilen auch ausgenommen.', 1),
(8, NULL, 'Chatbot', 'Entwickel einen Chatbot der auf Nutzereingaben reagieren kann und/oder Befehle annehmen kann. Für die Testphase ist es dabei nicht relevant, ob dieser über Discord oder über eine Webseite funktioniert.', 1),
(9, NULL, 'API / Dienst für Entwickler', 'Stelle eine API bereit. Ob abgesichert oder nicht, spielt keine Rolle. Diese muss ein Problem für andere Programmierer lösen, also von anderen Entwicklern genutzt werden können, um Teilprobleme, welche sie haben, an deinen Dienst auszulagern. Die Generierung von etwas oder auch die Verarbeitung von etwas ist hier gemeint.', 1),
(10, NULL, 'Website Crawler', 'Ein Crawler der min. eine Seite einer Webseite auf etwas prüft.', 1),
(11, 2, 'Geheimes Teilen', 'Entwickel eine Plattform, auf welcher man Dinge hochladen kann. Etwa ein Bild oder ein Video oder ein Text oder auch andere Dinge. Diese dürfen nach dem Hochladen nur noch über einen nicht zu erratenden Link vorgefunden werden, damit man diese teilen kann. ', 1),
(12, NULL, 'Form Builder', 'Erstelle ein Tool welches auf einer gegebenen Konfiguration ein HTML Formular erzeugt.', 1),
(13, NULL, 'Ene mene muh, ich vergeß nichts mehr in nuh', 'Jeder kennt es, kurz was gesehen, gedacht, überlegt und im nächsten Augenblick ist es wieder aus dem Kopf.\r\nHier bist du gefragt dieses zu ändern.', 1),
(14, NULL, 'Veni, vidi, vici', 'Ich kam, ich sah, ich siegte.\r\nUnter diesem Motto dürfen wir endlich ein kleines Spiel entwickeln :)', 1),
(16, NULL, 'Time is money', 'Ja Zeit und Geld, beides was keiner von uns wohl ausreichend hat.\r\nAber wie kriegt man beides am optimalsten gemanagt?', 1),
(17, NULL, 'Trash is our Problem', 'Jeder von uns steht ab und an vor dem Problem, das es Informationen oder ähnliches gibt, die nur kurzfristig benötigt wird.\r\nWohin mit diesen Trash, wie damit umgehen bevor uns der Müllberg übermannt?', 1),
(18, NULL, 'Mediale Überflut', 'Ohje schon wieder Song und da wieder ein Film, mist ein Hörspiel trudelt auch wieder ein.\r\nWohin nur damit damit ich es je wieder finde. Stauben hier schon Perlen ein von denen keiner mehr etwas weiß?\r\nWas können wir nur gegen diesen Zustand machen?', 1),
(20, NULL, 'Todo liste', 'Maybe das man eine ToDo liste erstellt wobei dann auch die Funktionalität und das design wichtig ist\r\n', 1),
(23, NULL, 'Mache etwas was etwas macht', 'Das Projekt soll etwas selbst erstellen, was erstellt wird ist dabei vollkommen frei wählbar, sei es ein Text, ein Bild, ein Video, ein Game oder andere Applikation oder etwas ganz anderes. \r\nWichtig ist, was erstellt wird wird durch die Applikation selbst erstellt, nicht durch den Benutzer. Dieser kann höchstens eine Vorlage, einen Seed, eine Eingabe liefern aus der dann etwas generiert wird, nicht mehr.', 1),
(24, NULL, 'Der Bug', 'Erstelle eine Anwendung deren zentrale Komponente ein Bug ist oder sich in freier Auslegung des Wortes darum dreht.', 1),
(25, NULL, 'Support the Dev', 'Baue eine Applikation die einen (PHP-)Entwickler bei der Arbeit unterstützt. Dies könnte z.B. eine Hilfe sein für Dinge die man immer wieder macht oder nach denen man immer wieder suchen muss und nicht auswendig im Kopf hat.', 1),
(26, NULL, 'Wieviel genau?', 'Baue eine Applikation bei der der User etwas schätzen/raten oder vergleichen muss.', 1),
(27, NULL, 'Stay alive', 'Baue eine Applikation bei der der User so lange wie möglich durch Aktionen aktiv/am Leben bleiben muss. Diese Aktion kann z.B. Geschwindigkeit, Geschick oder auch Wissen erfordern. Wer am längsten schafft gewinnt!', 1),
(28, NULL, 'Retro', 'Baue eine Retro-Website die aussieht/sich verhält wie eine Uraltwebsite, heißt zu Zeiten als das Internet noch neu war.', 1),
(29, NULL, 'Ein Chatroom', 'Ein Chat indem Leute sich treffen können und zusammen schreiben können', NULL);

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
  `lastLogin` datetime DEFAULT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `User`
--

INSERT INTO `User` (`id`, `roleId`, `name`, `password`, `email`, `registrationTime`, `lastLogin`, `active`) VALUES
(1, 1, 'BibaltiK', '$2y$10$TSmmEWhf9/7XPoxvyyXibuTxp7meo.mnQ.eEu4gja16Zp6gpHRzCW', 'bibaltik@exdrals.de', '2021-11-02 18:19:31', NULL, 1),
(2, 3, 'namePlayer', '$2y$10$bw3Qu7yzUdPoTXyGuc1Tf.7ejHUAXni/gIigCRgJXxqnInM0uPX1C', 'robin@npcore.net', '2021-11-11 06:36:22', NULL, 1),
(3, 3, 'iZy', '$2y$10$T8o.fG.g6P1T4Ujf2d1z..2/AM7dQRe4SpLYOqpdZdgeens0O4ucK', 'sklaus86@gmail.com', '2021-11-11 11:26:08', NULL, 1),
(4, 3, 'martincodes', '$2y$10$PKPxsXQ6pYmrU4rw9H/dbew0y6J0YczWo03/DIPX9KJ4r67YJ.Dcu', '', '2021-11-11 13:56:12', NULL, 1),
(5, 3, 'DaBu', '$2y$10$yG5A1XjS//RBBGcJz0wAoeKR5VQkaXEdck8V4V507Ay7jrowNGMmK', 'info@magnus-reiss.de', '2021-11-11 20:47:56', NULL, 1),
(6, 3, 'geste', '$2y$10$ZHiTepQm62b2bZHrmUUEr.NeB/qDdU5gpHZbnHXnEX2FwxNzFKvG.', 'geste35@aol.com', '2021-11-12 15:39:33', NULL, 1),
(7, 3, 'AC4GPutin', '$2y$10$k0NinDxjNI4ed9UGFuF.uuSJqpjdjaVqW41V3yhOyEfri98O91jKG', 'vost_400@gmx.de', '2021-11-12 18:03:08', NULL, 1),
(8, 3, 'TheAnfänger', '$2y$10$oG8JhXIWsM9tJVSEiKro.ONM9/H4NlFrx1jj1OlYJy7C4YKT18Ndi', 'ricovier699@gmail.com', '2021-11-13 23:47:12', NULL, 1),
(9, 3, 'Xatenev', '123456', NULL, '2022-04-10 23:22:23', '2022-04-10 23:22:06', 1),
(11, 3, 'maiki', '123456', NULL, '2022-04-10 23:24:10', '2022-04-10 23:23:57', 1);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT für Tabelle `Project`
--
ALTER TABLE `Project`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `TopicPool`
--
ALTER TABLE `TopicPool`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT für Tabelle `User`
--
ALTER TABLE `User`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
