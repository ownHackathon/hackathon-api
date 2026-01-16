<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921210259CreateGarbageContainerTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('GarbageContainer');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('userId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('tableId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('referenceId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('reasonId', Types::INTEGER, ['unsigned' => true,]);
        $table->addColumn('userDefinedReason', Types::STRING, ['length' => 255, 'default' => '',]);
        $table->addColumn('deletedAt', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP',]);

        $table->setPrimaryKey(['id']);

        $schema->getTable('GarbageContainer')->addForeignKeyConstraint(
            'Role',
            ['userId'],
            ['id'],
            name: 'fk_GarbageContainer_User_idx'
        );
        $schema->getTable('GarbageContainer')->addForeignKeyConstraint(
            'GarbageTable',
            ['tableId'],
            ['id'],
            name: 'fk_GarbageContainer_GarbageTable_idx'
        );
        $schema->getTable('GarbageContainer')->addForeignKeyConstraint(
            'GarbageReasonType',
            ['reasonId'],
            ['id'],
            name: 'fk_GarbageContainer_GarbageReasonType_idx'
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('GarbageContainer');
    }
}
