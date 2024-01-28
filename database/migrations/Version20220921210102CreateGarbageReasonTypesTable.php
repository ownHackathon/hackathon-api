<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921210102CreateGarbageReasonTypesTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('GarbageReasonType');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('name', Types::STRING, ['length' => 50,]);

        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('GarbageReasonType');
    }
}
