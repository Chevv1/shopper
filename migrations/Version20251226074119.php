<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251226074119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('profiles');

        $table->addColumn(name: 'id', typeName: 'guid', options: ['notnull' => true]);
        $table->addColumn(name: 'user_id', typeName: 'guid', options: ['notnull' => true]);
        $table->addColumn(name: 'avatar_id', typeName: 'guid', options: ['notnull' => false]);
        $table->addColumn(name: 'name', typeName: 'string', options: ['length' => 50, 'notnull' => true]);
        $table->addColumn(name: 'created_at', typeName: 'datetime_immutable', options: ['notnull' => true]);
        $table->addColumn(name: 'updated_at', typeName: 'datetime_immutable', options: ['notnull' => true]);

        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create());
        $table->addUniqueIndex(['user_id'], 'uniq_profiles_user_id');

        $table->addForeignKeyConstraint(
            foreignTableName: 'users',
            localColumnNames: ['user_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade', 'onUpdate' => 'cascade'],
        );

        $table->addForeignKeyConstraint(
            foreignTableName: 'images',
            localColumnNames: ['avatar_id'],
            foreignColumnNames: ['id'],
            options: ['onDelete' => 'cascade', 'onUpdate' => 'cascade'],
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('profiles');
    }
}
