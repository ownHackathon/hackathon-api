<?php declare(strict_types=1);

namespace ownHackathon\Migrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20231103224047_CreateTokenTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('Token');

        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('accountId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('token', Types::GUID, );
        $table->addColumn('tokenType', Types::SMALLINT, ['unsigned' => true, 'length' => 2,]);
        $table->addColumn('createdAt', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP',]);

        $table->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()
                ->setUnquotedColumnNames('id')
                ->create()
        );
        $table->addUniqueIndex(['token'], 'token_token_UNIQUE');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('Token');
    }
}
