<?php declare(strict_types=1);

namespace TestDataMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20990921210156_InsertAccountTestData extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $sql = <<<SQL
              INSERT INTO `Account` (`uuid`, `name`, `password`, `email`) VALUES
                ('0192f76a139d7eb382df26219fb0fa2e', 'Owner', '$2y$10$1O6FG7z6ozavsNXWRcLdIuTyxH79y9iySKuiGNb9fGbjiFaa6K.wa', 'owner@example.com'),
                ('0192f76b0b4e7a4bb4d872eb2f491066', 'Administrator', '$2y$10$9PfaGkyQYc8GK8a3nRSbSupxUTrX/yXsMNqWawZ7FAhTVBv3azSf.', 'admin@example.com'),
                ('0192f76d06747f13b77d36d958ffd32d', 'Moderator', '$2y$10$79SWSxmeKTswZS2xvkJ/reQ10QWfyYAvSb/J9L0S4XxAmYUhlVIo2', 'moderator@example.com'),
                ('0192f770b91d7b57893d170d52ebfdd1', 'User', '$2y$10$4mhYlAiwxUe84dqnEMmACOxek12Hjn5NlyF1DBiH1ginLjSCn.fPu', 'user@example.com'),
                ('0192cfe9233172cd972f837375c8f53e', 'Account Name', '$2y$10$4dye9Kn.UQkHDalnu9FS2uGwPgCZfrB0qYYJxUIcD8SdUQi8xDtp6', 'valid@example.com');
        SQL;

        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $sql = <<<SQL
              DELETE FROM `Account`; 
        SQL;

        $this->addSql($sql);
    }
}
