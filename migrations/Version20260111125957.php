<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260111125957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(name: 'cart_items');

        $table->addColumn(name: 'cart_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'product_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'quantity', typeName: 'integer', options: ['default' => 1, 'notnull' => true]);
        $table->addColumn(name: 'price', typeName: 'integer', options: ['notnull' => true]);

        $table->addIndex(['cart_id'], 'idx_cart_item_cart_id');

        $table->addForeignKeyConstraint(
            foreignTableName: 'carts',
            localColumnNames: ['cart_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade', 'onUpdate' => 'cascade'],
        );

        $table->addForeignKeyConstraint(
            foreignTableName: 'products',
            localColumnNames: ['product_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade', 'onUpdate' => 'cascade'],
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(name: 'cart_items');
    }
}
