<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921184501CreateEventTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('Event');

        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('uuid', Types::STRING, ['length' => 32]);
        $table->addColumn('userId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('title', Types::STRING);
        $table->addColumn('description', Types::STRING, ['default' => '']);
        $table->addColumn('eventText', Types::TEXT);
        $table->addColumn('createTime', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('startTime', Types::DATETIME_IMMUTABLE);
        $table->addColumn('duration', Types::SMALLINT, ['unsigned' => true,]);
        $table->addColumn('status', Types::SMALLINT, ['unsigned' => true, 'default' => 1,]);
        $table->addColumn('ratingCompleted', Types::BOOLEAN, ['default' => false]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['title'], 'event_title_UNIQUE');

        $schema->getTable('Event')->addForeignKeyConstraint(
            'User',
            ['userId'],
            ['id'],
            name: 'fk_Event_User_idx'
        );
    }

    public function down(Schema $schema): void
    {
        $schema->getTable('Event')->dropIndex('fk_Event_User_idx');
        $schema->dropTable('Event');
    }
}
