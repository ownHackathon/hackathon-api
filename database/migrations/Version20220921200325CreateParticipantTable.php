<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921200325CreateParticipantTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('Participant');

        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('userId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('eventId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('requestedAt', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP',]);
        $table->addColumn('subscribed', Types::BOOLEAN, ['default' => true,]);
        $table->addColumn('disqualified', Types::BOOLEAN, ['default' => false,]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['userId', 'eventId'], 'UNIQUE_USER_EVENT');

        $schema->getTable('Participant')->addForeignKeyConstraint(
            'Event',
            ['eventId'],
            ['id'],
            name: 'fk_Participant_Event_idx'
        );
        $schema->getTable('Participant')->addForeignKeyConstraint(
            'User',
            ['userId'],
            ['id'],
            name: 'fk_Participant_User_idx'
        );
    }

    public function down(Schema $schema): void
    {
        $schema->getTable('Participant')->dropIndex('fk__Participant_User_idx');
        $schema->getTable('Participant')->dropIndex('fk_Participant_Event_idx');
        $schema->dropTable('Participant');
    }
}
