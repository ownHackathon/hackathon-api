<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921204537CreateConstraint extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $schema->getTable('hack_User')->addForeignKeyConstraint('hack_Role', ['roleId'], ['id'], name: 'fk_hack_User_hack_Role_idx');
        $schema->getTable('hack_Event')->addForeignKeyConstraint('hack_User', ['userId'], ['id'], name: 'fk_hack_Event_hack_User_idx');
        $schema->getTable('hack_TopicPool')->addForeignKeyConstraint(
            'hack_Event',
            ['eventId'],
            ['id'],
            name: 'fk_hack_TopicPool_hack_Event_idx'
        );
        $schema->getTable('hack_Project')->addForeignKeyConstraint(
            'hack_Participant',
            ['participantId'],
            ['id'],
            name: 'fk_hack_Project_hack_Participant_idx'
        );
        $schema->getTable('hack_Participant')->addForeignKeyConstraint(
            'hack_Event',
            ['eventId'],
            ['id'],
            name: 'fk_hack_Participant_hack_Event_idx'
        );
        $schema->getTable('hack_Participant')->addForeignKeyConstraint(
            'hack_User',
            ['userId'],
            ['id'],
            name: 'fk_hack_Participant_User_idx'
        );
    }

    public function down(Schema $schema): void
    {
        $schema->getTable('hack_Participant')->dropIndex('fk_hack_Participant_hack_User_idx');
        $schema->getTable('hack_Participant')->dropIndex('fk_hack_Participant_hack_Event_idx');
        $schema->getTable('hack_Project')->dropIndex('fk_hack_Project_hack_Participant_idx');
        $schema->getTable('hack_TopicPool')->dropIndex('fk_hack_TopicPool_hack_Event_idx');
        $schema->getTable('hack_Event')->dropIndex('fk_hack_Event_hack_User_idx');
        $schema->getTable('hack_User')->dropIndex('fk_hack_User_hack_Role_idx');
    }
}
