<?php declare(strict_types=1);

namespace TestDataMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20990921210157_InsertWorkspaceTestData extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $sql = <<<SQL
              INSERT INTO `Workspace` (`accountId`, `name`, `slug`) VALUES
                (1, 'Owner', 'owner'),
                (2, 'Administrator', 'administrator'),
                (3, 'Moderator', 'moderator'),
                (4, 'User', 'user'),
                (5, 'Account Name', 'account-name');
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
