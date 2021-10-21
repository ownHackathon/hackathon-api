SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0;
SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0;
SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE =
        'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';


DROP TABLE IF EXISTS `Comment`;
DROP TABLE IF EXISTS `ProjectBlog`;
DROP TABLE IF EXISTS `Rating`;
DROP TABLE IF EXISTS `EventRatingCategory`;
DROP TABLE IF EXISTS `EventRating`;
DROP TABLE IF EXISTS `RatingCategory`;
DROP TABLE IF EXISTS `Project`;
DROP TABLE IF EXISTS `Participant`;
DROP TABLE IF EXISTS `Event`;
DROP TABLE IF EXISTS `User`;
DROP TABLE IF EXISTS `Role`;



CREATE TABLE IF NOT EXISTS `Role`
(
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(50)  NOT NULL,
    `description` VARCHAR(255) NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `role_UNIQUE` (`name` ASC)
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `User`
(
    `id`               INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `roleId`           INT UNSIGNED        NOT NULL DEFAULT 1,
    `name`             VARCHAR(50)         NOT NULL,
    `password`         VARCHAR(60)         NOT NULL,
    `email`            VARCHAR(300)        NULL,
    `registrationTime` DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `lastLogin`        DATETIME            NULL,
    `active`           TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `name_UNIQUE` (`name` ASC),
    UNIQUE INDEX `email_UNIQUE` (`email` ASC),
    INDEX `fk_User_Role_idx` (`roleId` ASC),
    CONSTRAINT `fk_User_Role`
        FOREIGN KEY (`roleId`)
            REFERENCES `Role` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `Event`
(
    `id`              INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `userId`          INT UNSIGNED        NOT NULL,
    `name`            VARCHAR(255)        NOT NULL,
    `description`     VARCHAR(255)        NULL,
    `eventText`       VARCHAR(8192)       NOT NULL,
    `createTime`      DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `startTime`       DATETIME            NOT NULL,
    `duration`        SMALLINT UNSIGNED   NOT NULL DEFAULT 7,
    `active`          TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    `ratingCompleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `name_UNIQUE` (`name` ASC),
    INDEX `fk_Event_User_idx` (`userId` ASC),
    CONSTRAINT `fk_Event_User`
        FOREIGN KEY (`userId`)
            REFERENCES `User` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `Participant`
(
    `id`           INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `userId`       INT UNSIGNED        NOT NULL,
    `eventId`      INT UNSIGNED        NOT NULL,
    `requestTime`  DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `approved`     TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `disqualified` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `fk_Participant_User_idx` (`userId` ASC),
    INDEX `fk_Participant_Event_idx` (`eventId` ASC),
    CONSTRAINT `fk_Participant_User`
        FOREIGN KEY (`userId`)
            REFERENCES `User` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE,
    CONSTRAINT `fk_Participant_Event`
        FOREIGN KEY (`eventId`)
            REFERENCES `Event` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `Comment`
(
    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `eventId`    INT UNSIGNED  NOT NULL,
    `userId`     INT UNSIGNED  NOT NULL,
    `Comment`    VARCHAR(8192) NOT NULL,
    `createTime` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    INDEX `fk_Comment_Event_idx` (`eventId` ASC),
    INDEX `fk_Comment_User_idx` (`userId` ASC),
    CONSTRAINT `fk_Comment_Event`
        FOREIGN KEY (`eventId`)
            REFERENCES `Event` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE,
    CONSTRAINT `fk_Comment_User`
        FOREIGN KEY (`userId`)
            REFERENCES `User` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE

)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `Project`
(
    `id`            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `participantId` INT UNSIGNED  NOT NULL,
    `title`         VARCHAR(255)  NOT NULL,
    `description`   VARCHAR(8192) NOT NULL,
    `gitRepoUri`    VARCHAR(2048) NOT NULL,
    `demoPageUri`   VARCHAR(2048) NULL,
    `createTime`    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    INDEX `fk_Project_Participant_idx` (`participantId` ASC),
    CONSTRAINT `fk_Project_Participant`
        FOREIGN KEY (`participantId`)
            REFERENCES `Participant` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `ProjectBlog`
(
    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `projectId`  INT UNSIGNED  NOT NULL,
    `title`      VARCHAR(255)  NULL,
    `blogText`   VARCHAR(8192) NOT NULL,
    `createTime` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    INDEX `fk_ProjectBlog_Project_idx` (`projectId` DESC),
    CONSTRAINT `fk_ProjectBlog_Project`
        FOREIGN KEY (`projectId`)
            REFERENCES `Project` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `RatingCategory`
(
    `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `title`       VARCHAR(255)  NOT NULL,
    `description` VARCHAR(2048) NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `EventRating`
(
    `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `minPoints` INT UNSIGNED NOT NULL DEFAULT 0,
    `maxPoints` INT UNSIGNED NOT NULL DEFAULT 6,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `EventRatingCategory`
(
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `eventId`          INT UNSIGNED NOT NULL,
    `ratingCategoryId` INT UNSIGNED NOT NULL,
    `weighting`        INT UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    INDEX `fk_EventRatingCategory_Event_idx` (`eventId` DESC),
    INDEX `fk_EventRatingCategory_RatingCategory_idx` (`ratingCategoryId` DESC),
    CONSTRAINT `fk_EventRatingCategory_Event`
        FOREIGN KEY (`eventId`)
            REFERENCES `Event` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE,
    CONSTRAINT `fk_EventRatingCategory_RatingCategory`
        FOREIGN KEY (`ratingCategoryId`)
            REFERENCES `RatingCategory` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `Rating`
(
    `id`                    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `userId`                INT UNSIGNED NOT NULL,
    `projectId`             INT UNSIGNED NOT NULL,
    `eventRatingId`         INT UNSIGNED NOT NULL,
    `eventRatingCategoryId` INT UNSIGNED NOT NULL,
    `rating`                INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_Rating_User_idx` (`userId` DESC),
    INDEX `fk_Rating_Project_idx` (`projectId` DESC),
    INDEX `fk_Rating_EventRating_idx` (`eventRatingId` DESC),
    INDEX `fk_Rating_EventRatingCategory_idx` (`eventRatingCategoryId` DESC),
    CONSTRAINT `fk_Rating_User`
        FOREIGN KEY (`userId`)
            REFERENCES `User` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE,
    CONSTRAINT `fk_Rating_Project`
        FOREIGN KEY (`projectId`)
            REFERENCES `Project` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE,
    CONSTRAINT `fk_Rating_EventRating`
        FOREIGN KEY (`eventRatingId`)
            REFERENCES `EventRating` (`id`)
            ON UPDATE NO ACTION
            ON DELETE CASCADE,
    CONSTRAINT `fk_Rating_EventRatingCategory`
        FOREIGN KEY (`eventRatingCategoryId`)
            REFERENCES `EventRatingCategory` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

SET SQL_MODE = @OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;
