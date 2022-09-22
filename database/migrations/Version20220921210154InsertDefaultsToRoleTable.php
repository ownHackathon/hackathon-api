<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921210154InsertDefaultsToRoleTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $sql = <<<SQL
                INSERT INTO 
                    `hack_Role` (`id`, `name`, `description`)
                VALUES 
                    (1, 'Owner', NULL),
                    (2, 'Administrator', NULL),
                    (3, 'Moderator', NULL),
                    (4, 'User', NULL);
        SQL;

        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $sql = <<<SQL
                DELETE FROM `hack_Role`;
                TRUNCATE `hack_Role`;
        SQL;

        $this->addSql($sql);
    }
}
