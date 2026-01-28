<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260112122733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(name: 'orders');

        $table->addColumn(name: 'id', typeName: 'guid', options: ['notnull' => true]);
        $table->addColumn(name: 'customer_id', typeName: 'guid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'status', typeName: 'string', options: ['length' => 30, 'notnull' => true]);
        $table->addColumn(name: 'total_price', typeName: 'integer', options: ['notnull' => true]);
        $table->addColumn(name: 'created_at', typeName: 'datetime_immutable', options: ['notnull' => true]);
        $table->addColumn(name: 'updated_at', typeName: 'datetime_immutable', options: ['notnull' => true]);

        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create());
        $table->addIndex(['customer_id'], 'idx_order_customer');
        $table->addIndex(['status'], 'idx_order_status');

        $table->addForeignKeyConstraint(
            foreignTableName: 'users',
            localColumnNames: ['customer_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade', 'onUpdate' => 'cascade'],
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(name: 'orders');
    }
}
