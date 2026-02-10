<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260209103238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(name: 'chat_messages');

        $table->addColumn(name: 'id', typeName: 'uuid', options: ['notnull' => true]);
        $table->addColumn(name: 'chat_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'sender_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'content', typeName: 'text');
        $table->addColumn(name: 'created_at', typeName: 'datetime_immutable', options: ['notnull' => true]);

        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames(firstColumnName: 'id')->create());
        $table->addIndex(columnNames: ['chat_id'], indexName: 'idx_chat_message_chat');

        $table->addForeignKeyConstraint(
            foreignTableName: 'chats',
            localColumnNames: ['chat_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade'],
        );

        $table->addForeignKeyConstraint(
            foreignTableName: 'chat_members',
            localColumnNames: ['sender_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade'],
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(name: 'chat_messages');
    }
}
