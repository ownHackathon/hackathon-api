<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20260121203207_CreateWorkspaceTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('Workspace');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('accountId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('name', Types::STRING, ['length' => 64, 'notnull' => false,]);
        $table->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()
                ->setUnquotedColumnNames('id')
                ->create()
        );
        $table->addUniqueIndex(['name'], 'workspace_name_UNIQUE');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('Workspace');
    }
}
