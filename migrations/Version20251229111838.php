<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251229111838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(name: 'product_images');

        $table->addColumn(name: 'product_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'image_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);

        $table->addIndex(['product_id'], 'idx_product_image_product');

        $table->addForeignKeyConstraint(
            foreignTableName: 'products',
            localColumnNames: ['product_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade', 'onUpdate' => 'cascade'],
        );

        $table->addForeignKeyConstraint(
            foreignTableName: 'images',
            localColumnNames: ['image_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade', 'onUpdate' => 'cascade'],
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(name: 'product_images');
    }
}
