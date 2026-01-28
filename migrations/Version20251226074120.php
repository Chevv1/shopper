<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251226074120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(name: 'products');

        $table->addColumn(name: 'id', typeName: 'guid', options: ['notnull' => true]);
        $table->addColumn(name: 'title', typeName: 'string', options: ['length' => 255, 'notnull' => true]);
        $table->addColumn(name: 'description', typeName: 'text', options: ['notnull' => true]);
        $table->addColumn(name: 'price', typeName: 'integer', options: ['notnull' => true]);
        $table->addColumn(name: 'seller_id', typeName: 'guid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'is_available', typeName: 'boolean', options: ['notnull' => true]);
        $table->addColumn(name: 'created_at', typeName: 'datetime_immutable', options: ['notnull' => true]);
        $table->addColumn(name: 'updated_at', typeName: 'datetime_immutable', options: ['notnull' => true]);

        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create());
        $table->addIndex(['seller_id'], 'idx_product_seller');
        $table->addIndex(['created_at'], 'idx_product_created_at');
        $table->addIndex(['updated_at'], 'idx_product_updated_at');

        $table->addForeignKeyConstraint(
            foreignTableName: 'users',
            localColumnNames: ['seller_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade', 'onUpdate' => 'cascade'],
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(name: 'products');
    }
}
