<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921202630CreateEventTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('hack_Event');

        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('userid', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('title', Types::STRING);
        $table->addColumn('description', Types::STRING);
        $table->addColumn('eventText', Types::TEXT);
        $table->addColumn('createTime', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('startTime', Types::DATETIME_IMMUTABLE);
        $table->addColumn('duration', Types::SMALLINT, ['unsigned' => true,]);
        $table->addColumn('status', Types::BOOLEAN, ['default' => 1,]);
        $table->addColumn('ratingCompleted', Types::BOOLEAN, ['default' => 0]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['title'], 'title_UNIQUE');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('hack_Event');
    }
}
