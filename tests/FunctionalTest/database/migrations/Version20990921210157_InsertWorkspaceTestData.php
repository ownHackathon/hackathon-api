<?php declare(strict_types=1);

namespace TestDataMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20990921210157_InsertWorkspaceTestData extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $sql = <<<SQL
              INSERT INTO `Workspace` (`accountId`, `name`) VALUES
                (1, 'Owner'),
                (2, 'Administrator'),
                (3, 'Moderator'),
                (4, 'User'),
                (5, 'Account Name');
        SQL;

        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $sql = <<<SQL
              DELETE FROM `Workspace`; 
        SQL;

        $this->addSql($sql);
    }
}
