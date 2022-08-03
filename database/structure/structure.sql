SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `Comment`
(
    `id`         int(10) UNSIGNED NOT NULL,
    `eventId`    int(10) UNSIGNED NOT NULL,
    `userId`     int(10) UNSIGNED NOT NULL,
    `Comment`    varchar(8192)    NOT NULL,
    `createTime` datetime         NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `Event`
(
    `id`              int(10) UNSIGNED     NOT NULL,
    `userId`          int(10) UNSIGNED     NOT NULL,
    `name`            varchar(255)         NOT NULL,
    `description`     varchar(255)                  DEFAULT NULL,
    `eventText`       varchar(8192)        NOT NULL,
    `createTime`      datetime             NOT NULL DEFAULT current_timestamp(),
    `startTime`       datetime             NOT NULL,
    `duration`        smallint(5) UNSIGNED NOT NULL DEFAULT 7,
    `status`          tinyint(1) UNSIGNED  NOT NULL DEFAULT 1,
    `ratingCompleted` tinyint(1) UNSIGNED  NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `EventRating`
(
    `id`        int(10) UNSIGNED NOT NULL,
    `minPoints` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `maxPoints` int(10) UNSIGNED NOT NULL DEFAULT 6
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `EventRatingCategory`
(
    `id`               int(10) UNSIGNED NOT NULL,
    `eventId`          int(10) UNSIGNED NOT NULL,
    `ratingCategoryId` int(10) UNSIGNED NOT NULL,
    `weighting`        int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `Participant`
(
    `id`           int(10) UNSIGNED    NOT NULL,
    `userId`       int(10) UNSIGNED    NOT NULL,
    `eventId`      int(10) UNSIGNED    NOT NULL,
    `requestTime`  datetime            NOT NULL DEFAULT current_timestamp(),
    `approved`     tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
    `disqualified` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `Project`
(
    `id`            int(10) UNSIGNED NOT NULL,
    `participantId` int(10) UNSIGNED NOT NULL,
    `title`         varchar(255)     NOT NULL,
    `description`   varchar(8192)    NOT NULL,
    `gitRepoUri`    varchar(2048)    NOT NULL,
    `demoPageUri`   varchar(2048)             DEFAULT NULL,
    `createTime`    datetime         NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `ProjectBlog`
(
    `id`         int(10) UNSIGNED NOT NULL,
    `projectId`  int(10) UNSIGNED NOT NULL,
    `title`      varchar(255)              DEFAULT NULL,
    `blogText`   varchar(8192)    NOT NULL,
    `createTime` datetime         NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `Rating`
(
    `id`                    int(10) UNSIGNED NOT NULL,
    `userId`                int(10) UNSIGNED NOT NULL,
    `projectId`             int(10) UNSIGNED NOT NULL,
    `eventRatingId`         int(10) UNSIGNED NOT NULL,
    `eventRatingCategoryId` int(10) UNSIGNED NOT NULL,
    `rating`                int(10) UNSIGNED NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `RatingCategory`
(
    `id`          int(10) UNSIGNED NOT NULL,
    `title`       varchar(255)     NOT NULL,
    `description` varchar(2048) DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `Role`
(
    `id`          int(10) UNSIGNED NOT NULL,
    `name`        varchar(50)      NOT NULL,
    `description` varchar(255) DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `TopicPool`
(
    `id`          int(10) UNSIGNED NOT NULL,
    `eventId`     int(10) UNSIGNED    DEFAULT NULL,
    `topic`       varchar(1024)    NOT NULL,
    `description` varchar(8096)       DEFAULT NULL,
    `accepted`    tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `User`
(
    `id`               int(10) UNSIGNED            NOT NULL,
    `uuid`             char(36) CHARACTER SET utf8 NOT NULL,
    `roleId`           int(10) UNSIGNED            NOT NULL DEFAULT 1,
    `name`             varchar(50)                 NOT NULL,
    `password`         varchar(60)                 NOT NULL,
    `email`            varchar(300)                         DEFAULT NULL,
    `registrationTime` datetime                    NOT NULL DEFAULT current_timestamp(),
    `lastAction`       datetime                             DEFAULT NULL,
    `active`           tinyint(1) UNSIGNED         NOT NULL DEFAULT 1
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

ALTER TABLE `Comment`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_Comment_Event_idx` (`eventId`),
    ADD KEY `fk_Comment_User_idx` (`userId`);

ALTER TABLE `Event`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `name_UNIQUE` (name),
    ADD KEY `fk_Event_User_idx` (`userId`);

ALTER TABLE `EventRating`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `EventRatingCategory`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_EventRatingCategory_Event_idx` (`eventId`),
    ADD KEY `fk_EventRatingCategory_RatingCategory_idx` (`ratingCategoryId`);

ALTER TABLE `Participant`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_Participant_User_idx` (`userId`),
    ADD KEY `fk_Participant_Event_idx` (`eventId`);

ALTER TABLE `Project`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_Project_Participant_idx` (`participantId`);

ALTER TABLE `ProjectBlog`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_ProjectBlog_Project_idx` (`projectId`);

ALTER TABLE `Rating`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_Rating_User_idx` (`userId`),
    ADD KEY `fk_Rating_Project_idx` (`projectId`),
    ADD KEY `fk_Rating_EventRating_idx` (`eventRatingId`),
    ADD KEY `fk_Rating_EventRatingCategory_idx` (`eventRatingCategoryId`);

ALTER TABLE `RatingCategory`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `Role`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `role_UNIQUE` (`name`);

ALTER TABLE `TopicPool`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_TopicPool_Event_idx` (`eventId`),
    ADD KEY `fk_TopicPool_accepted_idx` (`accepted`);

ALTER TABLE `User`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `name_UNIQUE` (`name`),
    ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`) USING BTREE,
    ADD UNIQUE KEY `email_UNIQUE` (`email`),
    ADD KEY `fk_User_Role_idx` (`roleId`);

ALTER TABLE `Comment`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `Event`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `EventRating`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `EventRatingCategory`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `Participant`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `Project`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `ProjectBlog`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `Rating`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `RatingCategory`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `Role`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `TopicPool`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `User`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `Comment`
    ADD CONSTRAINT `fk_Comment_Event` FOREIGN KEY (`eventId`) REFERENCES `Event` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_Comment_User` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE `Event`
    ADD CONSTRAINT `fk_Event_User` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `EventRatingCategory`
    ADD CONSTRAINT `fk_EventRatingCategory_Event` FOREIGN KEY (`eventId`) REFERENCES `Event` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_EventRatingCategory_RatingCategory` FOREIGN KEY (`ratingCategoryId`) REFERENCES `RatingCategory` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE `Participant`
    ADD CONSTRAINT `fk_Participant_Event` FOREIGN KEY (`eventId`) REFERENCES `Event` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_Participant_User` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE `Project`
    ADD CONSTRAINT `fk_Project_Participant` FOREIGN KEY (`participantId`) REFERENCES `Participant` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE `ProjectBlog`
    ADD CONSTRAINT `fk_ProjectBlog_Project` FOREIGN KEY (`projectId`) REFERENCES `Project` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE `Rating`
    ADD CONSTRAINT `fk_Rating_EventRating` FOREIGN KEY (`eventRatingId`) REFERENCES `EventRating` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_Rating_EventRatingCategory` FOREIGN KEY (`eventRatingCategoryId`) REFERENCES `EventRatingCategory` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_Rating_Project` FOREIGN KEY (`projectId`) REFERENCES `Project` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_Rating_User` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE `User`
    ADD CONSTRAINT `fk_User_Role` FOREIGN KEY (`roleId`) REFERENCES `Role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
