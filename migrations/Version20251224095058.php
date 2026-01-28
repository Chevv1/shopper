<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251224095058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create users table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('users');

        $table->addColumn('id', 'guid', ['notnull' => true]);
        $table->addColumn('email', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('password', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('roles', 'json', ['notnull' => true, 'default' => '["ROLE_USER"]']);
        $table->addColumn('created_at', 'datetime_immutable', ['notnull' => true]);
        $table->addColumn('updated_at', 'datetime_immutable', ['notnull' => true]);

        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create());
        $table->addUniqueIndex(['email'], 'uniq_users_email');
        $table->addIndex(['email'], 'idx_user_email');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('users');
    }
}
