<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921185916CreateRoleTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('hack_Role');

        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('name', Types::STRING, ['length' => 50]);
        $table->addColumn('description', Types::TEXT, ['notnull' => false]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name'], 'name_UNIQUE');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('hack_Role');
    }
}
