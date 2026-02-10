<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260209103236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('chat_members');
        $table->addColumn(name: 'id', typeName: 'uuid', options: ['notnull' => true]);
        $table->addColumn(name: 'chat_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'user_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'role', typeName: 'string', options: ['length' => 50]);
        $table->addColumn(name: 'joined_at', typeName: 'datetime_immutable', options: ['notnull' => true]);

        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames(firstColumnName: 'id')->create());
        $table->addIndex(columnNames: ['chat_id'], indexName: 'idx_chat_member_chat');
        $table->addUniqueIndex(columnNames: ['chat_id', 'user_id'], indexName: 'UNIQ_CHAT_USER');

        $table->addForeignKeyConstraint(
            foreignTableName: 'chats',
            localColumnNames: ['chat_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade'],
        );

        $table->addForeignKeyConstraint(
            foreignTableName: 'users',
            localColumnNames: ['user_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade'],
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('chat_members');
    }
}
