<?php declare(strict_types=1);

namespace ownHackathon\Migrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20260219222601_CreateEventTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('Event');
        $table->addColumn('id', Types::INTEGER, [
            'autoincrement' => true,
            'unsigned' => true,
        ]);
        $table->addColumn('uuid', Types::GUID,);
        $table->addColumn('workspaceId', Types::INTEGER, [
            'unsigned' => true,
        ]);
        $table->addColumn('topicId', Types::INTEGER, [
            'unsigned' => true,
            'notnull' => false
        ]);
        $table->addColumn('name', Types::STRING, [
            'length' => 64,
        ]);
        $table->addColumn('slug', Types::STRING, [
            'length' => 64,
        ]);
        $table->addColumn('description', Types::STRING, [
            'length' => 255,
            'notnull' => false,
        ]);
        $table->addColumn('details', Types::TEXT, [
            'notnull' => false,
            'default' => ''
        ]);
        $table->addColumn('status', Types::SMALLINT, [
            'unsigned' => true,
            'default' => 1
        ]);
        $table->addColumn('visibility', Types::SMALLINT, [
            'unsigned' => true,
            'default' => 1
        ]);
        $table->addColumn('beginsOn', Types::DATETIME_IMMUTABLE, [
            'notnull' => false,
        ]);
        $table->addColumn('endsOn', Types::DATETIME_IMMUTABLE, [
            'notnull' => false,
        ]);
        $table->addColumn('createdAt', Types::DATETIME_IMMUTABLE, [
            'default' => 'CURRENT_TIMESTAMP',
        ]);



        $table->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()
                ->setUnquotedColumnNames('id')
                ->create()
        );
        $table->addUniqueIndex(['uuid'], 'event_uuid_UNIQUE');
        $table->addUniqueIndex(['name'], 'event_name_UNIQUE');
        $table->addUniqueIndex(['slug'], 'event_slug_UNIQUE');

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('Event');
    }
}
