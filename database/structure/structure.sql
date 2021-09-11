SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0;
SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0;
SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE =
        'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';


DROP TABLE IF EXISTS `JamComment`;
DROP TABLE IF EXISTS `JamProjectBlog`;
DROP TABLE IF EXISTS `JamProject`;
DROP TABLE IF EXISTS `JamRating`;
DROP TABLE IF EXISTS `JamParticipant`;
DROP TABLE IF EXISTS `Jam`;
DROP TABLE IF EXISTS `User`;
DROP TABLE IF EXISTS `Role`;



CREATE TABLE IF NOT EXISTS `Role`
(
    `roleId`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `roleName`    VARCHAR(50)  NOT NULL,
    `description` VARCHAR(255) NULL,
    PRIMARY KEY (`roleId`),
    UNIQUE INDEX `role_UNIQUE` (`roleName` ASC)
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `User`
(
    `userId`           INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `roleId`           INT UNSIGNED        NOT NULL DEFAULT 1,
    `userName`         VARCHAR(50)         NOT NULL,
    `password`         VARCHAR(60)         NOT NULL,
    `email`            VARCHAR(300)        NULL,
    `registrationTime` DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `lastLogin`        DATETIME            NULL,
    `active`           TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (`userId`),
    UNIQUE INDEX `userName_UNIQUE` (`userName` ASC),
    UNIQUE INDEX `email_UNIQUE` (`email` ASC),
    INDEX `fk_User_Role_idx` (`roleId` ASC),
    CONSTRAINT `fk_User_Role`
        FOREIGN KEY (`roleId`)
            REFERENCES `Role` (`roleId`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `Jam`
(
    `jamId`       INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `userId`      INT UNSIGNED        NOT NULL,
    `jamName`     VARCHAR(255)        NOT NULL,
    `description` VARCHAR(255)        NULL,
    `jamText`     VARCHAR(8192)       NOT NULL,
    `createTime`  DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `duration`    SMALLINT UNSIGNED   NOT NULL DEFAULT 7,
    `active`      TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (`jamId`),
    UNIQUE INDEX `jamName_UNIQUE` (`jamName` ASC),
    INDEX `fk_Jam_User_idx` (`userId` ASC),
    CONSTRAINT `fk_Jam_User`
        FOREIGN KEY (`userId`)
            REFERENCES `User` (`userId`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `JamParticipant`
(
    `jamParticipantId` INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `userId`           INT UNSIGNED        NOT NULL,
    `jamId`            INT UNSIGNED        NOT NULL,
    `requestTime`      DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `approved`         TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `disqualified`     TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`jamParticipantId`),
    INDEX `fk_JamParticipant_User_idx` (`userId` ASC),
    INDEX `fk_JamParticipant_Jam_idx` (`jamId` ASC),
    CONSTRAINT `fk_JamParticipant_User`
        FOREIGN KEY (`userId`)
            REFERENCES `User` (`userId`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE,
    CONSTRAINT `fk_JamParticipant_Jam`
        FOREIGN KEY (`jamId`)
            REFERENCES `Jam` (`jamId`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `JamComment`
(
    `jamCommentId` INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `jamId`        INT UNSIGNED  NOT NULL,
    `userId`       INT UNSIGNED  NOT NULL,
    `jamComment`   VARCHAR(8192) NOT NULL,
    `createTime`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`jamCommentId`),
    INDEX `fk_JamComment_Jam_idx` (`jamId` ASC),
    INDEX `fk_JamComment_User_idx` (`userId` ASC),
    CONSTRAINT `fk_JamComment_Jam`
        FOREIGN KEY (`jamId`)
            REFERENCES `Jam` (`jamId`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE,
    CONSTRAINT `fk_JamComment_User`
        FOREIGN KEY (`userId`)
            REFERENCES `User` (`userId`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE

)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `JamProject`
(
    `jamProjectId`     INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `jamParticipantId` INT UNSIGNED  NOT NULL,
    `title`            VARCHAR(255)  NOT NULL,
    `description`      VARCHAR(8192) NOT NULL,
    `gitRepoUri`       VARCHAR(2048) NOT NULL,
    `demoPageUri`      VARCHAR(2048) NULL,
    `createTime`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`jamProjectId`),
    INDEX `fk_JamProject_JamParticipant_idx` (`jamParticipantId` ASC),
    CONSTRAINT `fk_JamProject_JamParticipant`
        FOREIGN KEY (`jamParticipantId`)
            REFERENCES `JamParticipant` (`jamParticipantId`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `JamProjectBlog`
(
    `jamProjectBlogId` INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `jamProjectId`     INT UNSIGNED  NOT NULL,
    `title`            VARCHAR(255)  NULL,
    `blogText`         VARCHAR(8192) NOT NULL,
    `createTime`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`jamProjectBlogId`),
    INDEX `fk_JamProjectBlog_JamProjects_idx` (`jamProjectId` DESC),
    CONSTRAINT `fk_JamProjectBlog_JamProjects`
        FOREIGN KEY (`jamProjectId`)
            REFERENCES `JamProject` (`jamProjectId`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `JamRating`
(
    jamRatingId      INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `userId`         INT UNSIGNED        NOT NULL,
    `jamProjectId`   INT UNSIGNED        NOT NULL,
    `creativity`     TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
    `implementation` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
    `structure`      TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
    `technology`     TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
    `scope`          TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
    `submitTime`     DATETIME            NULL,
    PRIMARY KEY (jamRatingId),
    INDEX `fk_JamRating_User_idx` (`userId` ASC),
    INDEX `fk_JamRating_JamProject_idx` (`jamProjectId` ASC),
    CONSTRAINT `fk_JamRating_User`
        FOREIGN KEY (`userId`)
            REFERENCES `User` (`userId`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE,
    CONSTRAINT `fk_JamRating_JamProjects`
        FOREIGN KEY (`jamProjectId`)
            REFERENCES `JamProject` (`jamProjectId`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

SET SQL_MODE = @OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;
