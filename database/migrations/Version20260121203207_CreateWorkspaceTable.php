<?php declare(strict_types=1);

namespace ownHackathon\Migrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20260121203207_CreateWorkspaceTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('Workspace');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true,]);
        $table->addColumn('uuid', Types::GUID,);
        $table->addColumn('accountId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('name', Types::STRING, ['length' => 64,]);
        $table->addColumn('slug', Types::STRING, ['length' => 64,]);
        $table->addColumn('description', Types::STRING, ['length' => 255, 'notnull' => false, 'default' => '']);

        $table->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()
                ->setUnquotedColumnNames('id')
                ->create()
        );
        $table->addUniqueIndex(['uuid'], 'workspace_uuid_UNIQUE');
        $table->addUniqueIndex(['name'], 'workspace_name_UNIQUE');
        $table->addUniqueIndex(['slug'], 'workspace_slug_UNIQUE');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('Workspace');
    }
}
