<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20231103224046_CreateAccountActivationTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('AccountActivation');

        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('email', Types::STRING, ['length' => 512,]);
        $table->addColumn('token', Types::STRING, ['length' => 32,]);
        $table->addColumn('createdAt', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP',]);

        $table->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()
                ->setUnquotedColumnNames('id')
                ->create()
        );
        $table->addUniqueIndex(['token'], 'account_activation_token_UNIQUE');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('AccountActivation');
    }
}
