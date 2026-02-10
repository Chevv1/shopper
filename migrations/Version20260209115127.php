<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260209115127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(name: 'payments');

        $table->addColumn(name: 'id', typeName: 'uuid', options: ['notnull' => true]);
        $table->addColumn(name: 'order_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'owner_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'method_id', typeName: 'uuid', options: ['length' => 36, 'notnull' => true]);
        $table->addColumn(name: 'status', typeName: 'string', options: ['length' => 30, 'notnull' => true]);
        $table->addColumn(name: 'amount', typeName: 'integer', options: ['notnull' => true]);
        $table->addColumn(name: 'url', typeName: 'text', options: ['notnull' => false]);
        $table->addColumn(name: 'created_at', typeName: 'datetime_immutable', options: ['notnull' => true]);
        $table->addColumn(name: 'updated_at', typeName: 'datetime_immutable', options: ['notnull' => true]);

        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames(firstColumnName: 'id')->create());

        $table->addForeignKeyConstraint(
            foreignTableName: 'orders',
            localColumnNames: ['order_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade'],
        );

        $table->addForeignKeyConstraint(
            foreignTableName: 'users',
            localColumnNames: ['owner_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade'],
        );

        $table->addForeignKeyConstraint(
            foreignTableName: 'payment_methods',
            localColumnNames: ['method_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade'],
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(name: 'payments');
    }
}
