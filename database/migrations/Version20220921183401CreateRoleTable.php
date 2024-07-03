<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921183401CreateRoleTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('Role');

        $table->addColumn('id', Types::INTEGER, ['autoincrement' => false, 'unsigned' => true,]);
        $table->addColumn('uuid', Types::STRING, ['length' => 32,]);
        $table->addColumn('name', Types::STRING, ['length' => 50,]);
        $table->addColumn('description', Types::TEXT, ['default' => '',]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name'], 'role_name_UNIQUE');

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('Role');
    }
}
