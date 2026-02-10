<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260109141804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(name: 'payment_methods');

        $table->addColumn(name: 'id', typeName: 'uuid', options: ['notnull' => true]);
        $table->addColumn(name: 'name', typeName: 'string', options: ['notnull' => true]);
        $table->addColumn(name: 'type', typeName: 'string', options: ['length' => 12, 'notnull' => true]);
        $table->addColumn(name: 'is_active', typeName: 'boolean', options: ['length' => 12, 'notnull' => true]);
        $table->addColumn(name: 'logo_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'created_at', typeName: 'datetime_immutable', options: ['notnull' => true]);
        $table->addColumn(name: 'updated_at', typeName: 'datetime_immutable', options: ['notnull' => true]);

        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create());
        $table->addIndex(['created_at'], 'idx_payment_method_created_at');
        $table->addIndex(['updated_at'], 'idx_payment_method_updated_at');

        $table->addForeignKeyConstraint(
            foreignTableName: 'images',
            localColumnNames: ['logo_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade', 'onUpdate' => 'cascade'],
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(name: 'payment_methods');
    }
}
