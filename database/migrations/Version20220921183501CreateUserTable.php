<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921183501CreateUserTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('User');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('uuid', Types::STRING, ['length' => 32,]);
        $table->addColumn('roleId', Types::INTEGER, ['unsigned' => true, 'default' => 1]);
        $table->addColumn('name', Types::STRING, ['length' => 50,]);
        $table->addColumn('password', Types::STRING);
        $table->addColumn('email', Types::STRING);
        $table->addColumn('registrationAt', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP',]);
        $table->addColumn('lastActionAt', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP',]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['uuid'], 'user_uuid_UNIQUE');
        $table->addUniqueIndex(['name'], 'user_name_UNIQUE');
        $table->addUniqueIndex(['email'], 'user_email_UNIQUE');

        $schema->getTable('User')->addForeignKeyConstraint(
            'Role',
            ['roleId'],
            ['id'],
            name: 'fk_User_Role_idx'
        );
    }

    public function down(Schema $schema): void
    {
        $schema->getTable('User')->dropIndex('fk_User_Role_idx');
        $schema->dropTable('User');
    }
}
