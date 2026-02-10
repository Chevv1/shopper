<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251226072912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(name: 'images');

        $table->addColumn(name: 'id', typeName: 'uuid', options: ['notnull' => true]);
        $table->addColumn(name: 'filename', typeName: 'string', options: ['length' => 255, 'notnull' => true]);
        $table->addColumn(name: 'path', typeName: 'string', options: ['length' => 500, 'notnull' => true]);
        $table->addColumn(name: 'mime_type', typeName: 'string', options: ['length' => 100, 'notnull' => true]);
        $table->addColumn(name: 'size', typeName: 'integer', options: ['notnull' => true]);
        $table->addColumn(name: 'width', typeName: 'integer', options: ['notnull' => true]);
        $table->addColumn(name: 'height', typeName: 'integer', options: ['notnull' => true]);
        $table->addColumn(name: 'owner_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'uploaded_at', typeName: 'datetime_immutable', options: ['notnull' => true]);

        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create());
        $table->addIndex(['owner_id'], 'idx_image_owner');
        $table->addIndex(['uploaded_at'], 'idx_image_uploaded_at');

        $table->addForeignKeyConstraint(
            foreignTableName: 'users',
            localColumnNames: ['owner_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade', 'onUpdate' => 'cascade'],
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('images');
    }
}
