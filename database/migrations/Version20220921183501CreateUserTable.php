<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921183501CreateUserTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('hack_User');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('uuid', Types::STRING, ['length' => 32]);
        $table->addColumn('roleId', Types::INTEGER, ['unsigned' => true]);
        $table->addColumn('name', Types::STRING, ['length' => 50]);
        $table->addColumn('password', Types::STRING);
        $table->addColumn('email', Types::STRING);
        $table->addColumn('registrationTime', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('lastAction', Types::DATETIME_IMMUTABLE, ['notnull' => false]);
        $table->addColumn('active', Types::BOOLEAN, ['default' => true]);
        $table->addColumn('token', Types::STRING, ['length' => 32, 'notnull' => false]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['uuid'], 'uuid_UNIQUE');
        $table->addUniqueIndex(['name'], 'name_UNIQUE');
        $table->addUniqueIndex(['email'], 'email_UNIQUE');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('hack_User');
    }
}
