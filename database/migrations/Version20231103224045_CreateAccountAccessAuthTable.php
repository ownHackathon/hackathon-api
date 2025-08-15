<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20231103224045_CreateAccountAccessAuthTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('AccountAccessAuth');

        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('accountId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('label', Types::STRING, ['length' => 64, 'default' => 'default']);
        $table->addColumn('refreshToken', Types::STRING, ['length' => 512,]);
        $table->addColumn('userAgent', Types::STRING, ['length' => 255, 'default' => 'unknown']);
        $table->addColumn('clientIdentHash', Types::STRING, ['length' => 128,]);
        $table->addColumn('createdAt', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP',]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['refreshToken'], 'account_access_auth_refresh_token_UNIQUE');
        $table->addUniqueIndex(['clientIdentHash'], 'account_access_auth_client_ident_hash_UNIQUE');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('AccountAccessAuth');
    }
}
