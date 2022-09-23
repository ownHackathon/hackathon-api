<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921204537CreateConstraint extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $schema->getTable('User')->addForeignKeyConstraint('Role', ['roleId'], ['id'], name: 'fk_User_Role_idx');
        $schema->getTable('Event')->addForeignKeyConstraint('User', ['userId'], ['id'], name: 'fk_Event_User_idx');
        $schema->getTable('TopicPool')->addForeignKeyConstraint(
            'Event',
            ['eventId'],
            ['id'],
            name: 'fk_TopicPool_Event_idx'
        );
        $schema->getTable('Project')->addForeignKeyConstraint(
            'Participant',
            ['participantId'],
            ['id'],
            name: 'fk_Project_Participant_idx'
        );
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
        $schema->getTable('Participant')->dropIndex('fk_hack_Participant_hack_User_idx');
        $schema->getTable('Participant')->dropIndex('fk_hack_Participant_hack_Event_idx');
        $schema->getTable('Project')->dropIndex('fk_hack_Project_hack_Participant_idx');
        $schema->getTable('TopicPool')->dropIndex('fk_hack_TopicPool_hack_Event_idx');
        $schema->getTable('Event')->dropIndex('fk_hack_Event_hack_User_idx');
        $schema->getTable('hack_User')->dropIndex('fk_hack_User_hack_Role_idx');
    }
}
