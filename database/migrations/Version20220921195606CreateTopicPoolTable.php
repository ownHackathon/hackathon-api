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
        $table->addColumn('eventId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('topic', Types::STRING);
        $table->addColumn('description', Types::TEXT);
        $table->addColumn('accepted', Types::BOOLEAN);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['topic'], 'topic_UNIQUE');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('TopicPool');
    }
}
