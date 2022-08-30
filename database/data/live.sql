SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `Role` (`id`, `name`, `description`)
VALUES (1, 'Administrator', NULL),
       (2, 'Moderator', NULL),
       (3, 'User', NULL);

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
