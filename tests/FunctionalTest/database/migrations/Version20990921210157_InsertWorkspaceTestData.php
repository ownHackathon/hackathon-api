<?php declare(strict_types=1);

namespace TestDataMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20990921210157_InsertWorkspaceTestData extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $sql = <<<SQL
              INSERT INTO `Workspace` (`uuid`, `accountId`, `name`, `slug`) VALUES
                (UUID_v7(), 1, 'Owner', 'owner'),
                (UUID_v7(), 2, 'Administrator', 'administrator'),
                (UUID_v7(), 3, 'Moderator', 'moderator'),
                (UUID_v7(), 4, 'User', 'user'),
                (UUID_v7(), 5, 'Account Name', 'account-name');
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
