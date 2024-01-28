<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921200425CreateProjectTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('Project');

        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('uuid', Types::STRING, ['length' => 32,]);
        $table->addColumn('participantId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('title', Types::STRING);
        $table->addColumn('description', Types::TEXT);
        $table->addColumn('createTime', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP',]);
        $table->addColumn('gitRepoUri', Types::STRING, ['length' => 2083, 'default' => '',]);
        $table->addColumn('demoPageUri', Types::STRING, ['length' => 2083, 'default' => '',]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['title'], 'project_title_UNIQUE');

        $schema->getTable('Project')->addForeignKeyConstraint(
            'Participant',
            ['participantId'],
            ['id'],
            name: 'fk_Project_Participant_idx'
        );
    }

    public function down(Schema $schema): void
    {
        $schema->getTable('Project')->dropIndex('fk_Project_Participant_idx');
        $schema->dropTable('Project');
    }
}
