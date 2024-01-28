<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921195606CreateTopicPoolTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('TopicPool');

        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('uuid', Types::STRING, ['length' => 32,]);
        $table->addColumn('eventId', Types::INTEGER, ['unsigned' => true, 'notnull' => false,]);
        $table->addColumn('topic', Types::STRING);
        $table->addColumn('description', Types::TEXT, ['default' => '',]);
        $table->addColumn('accepted', Types::BOOLEAN, ['default' => NULL, 'notnull' => false,]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['uuid'], 'topicpool_uuid_UNIQUE');
        $table->addUniqueIndex(['topic'], 'topicpool_topic_UNIQUE');

        $schema->getTable('TopicPool')->addForeignKeyConstraint(
            'Event',
            ['eventId'],
            ['id'],
            name: 'fk_TopicPool_Event_idx'
        );
    }

    public function down(Schema $schema): void
    {
        $schema->getTable('TopicPool')->dropIndex('fk_TopicPool_Event_idx');
        $schema->dropTable('TopicPool');
    }
}
