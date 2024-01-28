<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921220156InsertDefaultsToRoleTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $sql = <<<SQL
                INSERT INTO 
                    `Role` (`id`, `uuid`, `name`, `description`)
                VALUES 
                    (0, '018d418722067a61b009ff5ae58dcbdc', 'Guest', ''),
                    (1, '018d418722067a61b009ff5ae58dcbdc', 'User', ''),
                    (100, '018d41876b9a74538d3cc5ac312673ee', 'Moderator', ''),
                    (1000, '018d41878d4c71f08bb29966370b794b', 'Administrator', ''),
                    (999999, '018d4187ac6973b1a788b1bd9e1085b4', 'Owner', '');
        SQL;

        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $sql = <<<SQL
                DELETE FROM `Role`;
                TRUNCATE `Role`;
        SQL;

        $this->addSql($sql);
    }
}
