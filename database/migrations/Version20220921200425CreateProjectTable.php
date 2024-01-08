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
        $table->addColumn('participantId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('title', Types::STRING);
        $table->addColumn('description', Types::TEXT);
        $table->addColumn('gitRepoUri', Types::STRING, ['length' => 2083,]);
        $table->addColumn('demoPageUri', Types::STRING, ['length' => 2083,]);
        $table->addColumn('createTime', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['title'], 'project_title_UNIQUE');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('Project');
    }
}
