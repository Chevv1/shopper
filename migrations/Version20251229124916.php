<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251229124916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(name: 'product_units');

        $table->addColumn(name: 'id', typeName: 'guid', options: ['notnull' => true]);
        $table->addColumn(name: 'product_id', typeName: 'guid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'content', typeName: 'text', options: ['notnull' => true]);
        $table->addColumn(name: 'status', typeName: 'string', options: ['length' => 30, 'notnull' => true]);
        $table->addColumn(name: 'created_at', typeName: 'datetime_immutable', options: ['notnull' => true]);
        $table->addColumn(name: 'updated_at', typeName: 'datetime_immutable', options: ['notnull' => true]);

        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create());
        $table->addIndex(['product_id'], 'idx_product_unit_product');
        $table->addIndex(['status'], 'idx_product_unit_status');
        $table->addIndex(['created_at'], 'idx_product_unit_created_at');
        $table->addIndex(['updated_at'], 'idx_product_unit_updated_at');

        $table->addForeignKeyConstraint(
            foreignTableName: 'products',
            localColumnNames: ['product_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade', 'onUpdate' => 'cascade'],
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(name: 'product_units');
    }
}
