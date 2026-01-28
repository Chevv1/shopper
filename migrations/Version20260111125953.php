<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260111125953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(name: 'carts');

        $table->addColumn(name: 'id', typeName: 'guid', options: ['notnull' => true]);
        $table->addColumn(name: 'user_id', typeName: 'guid', options: ['length' => 36, 'notnull' => true]);

        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create());
        $table->addIndex(['user_id'], 'idx_cart_user_id');

        $table->addForeignKeyConstraint(
            foreignTableName: 'users',
            localColumnNames: ['user_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade', 'onUpdate' => 'cascade'],
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(name: 'carts');
    }
}
