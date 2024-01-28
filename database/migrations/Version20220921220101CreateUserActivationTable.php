<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921220101CreateUserActivationTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('UserActivation');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('userId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('token', Types::STRING, ['length' => 32,]);
        $table->addColumn('activationRequestTime', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP',]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['userId'], 'user_activation_userid_UNIQUE');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('UserActivation');
    }
}
