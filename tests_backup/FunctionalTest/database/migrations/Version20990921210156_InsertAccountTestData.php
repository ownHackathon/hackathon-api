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
                ('019bec80-2a10-79ed-ac43-7b8f6b31939b', 'Owner', '$2y$10$1O6FG7z6ozavsNXWRcLdIuTyxH79y9iySKuiGNb9fGbjiFaa6K.wa', 'owner@example.com'),
                ('019bec80-3ba4-791f-b243-9e81aa23469b', 'Administrator', '$2y$10$9PfaGkyQYc8GK8a3nRSbSupxUTrX/yXsMNqWawZ7FAhTVBv3azSf.', 'admin@example.com'),
                ('019bec80-4ce8-7b3b-9caa-046166b95e32', 'Moderator', '$2y$10$79SWSxmeKTswZS2xvkJ/reQ10QWfyYAvSb/J9L0S4XxAmYUhlVIo2', 'moderator@example.com'),
                ('019bec80-6ffc-78a9-8331-4fa708913738', 'User', '$2y$10$4mhYlAiwxUe84dqnEMmACOxek12Hjn5NlyF1DBiH1ginLjSCn.fPu', 'user@example.com'),
                ('019bec80-7ff5-7255-8d77-a0ce5a86dce6', 'Account Name', '$2y$10$4dye9Kn.UQkHDalnu9FS2uGwPgCZfrB0qYYJxUIcD8SdUQi8xDtp6', 'valid@example.com');
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
