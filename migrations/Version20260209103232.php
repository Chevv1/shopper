<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260209103232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('chats');
        $table->addColumn(name: 'id', typeName: 'uuid', options: ['notnull' => true]);
        $table->addColumn(name: 'correlation_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'correlation_type', typeName: 'string', options: ['length' => 255]);
        $table->addColumn(name: 'status', typeName: 'string', options: ['length' => 50]);
        $table->addColumn(name: 'created_at', typeName: 'datetime_immutable', options: ['notnull' => true]);
        $table->addColumn(name: 'updated_at', typeName: 'datetime_immutable', options: ['notnull' => true]);

        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create());

        $table->addIndex(
            columnNames: ['correlation_id', 'correlation_type'],
            indexName: 'idx_chat_correlation',
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('chats');
    }
}
