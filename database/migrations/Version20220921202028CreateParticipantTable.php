<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20220921202028CreateParticipantTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('Participant');

        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true,]);
        $table->addColumn('userId', Types::INTEGER, ['unsigned' => true]);
        $table->addColumn('eventId', Types::INTEGER, ['unsigned' => true]);
        $table->addColumn('requestTime', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('subscribed', Types::BOOLEAN, ['default' => true]);
        $table->addColumn('approved', Types::BOOLEAN, ['default' => true,]);
        $table->addColumn('disqualified', Types::BOOLEAN, ['default' => false]);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('Participant');
    }
}
