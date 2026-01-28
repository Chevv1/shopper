<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Uuid;

final class Version20260111173550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            sql: 'INSERT INTO categories (id, name, created_at, updated_at) VALUES (:id, :name, :created_at, :updated_at)',
            params: [
                'id' => Uuid::v4()->toString(),
                'name' => 'test category',
                'created_at' => new \DateTimeImmutable()->format('Y-m-d H:i:s'),
                'updated_at' => new \DateTimeImmutable()->format('Y-m-d H:i:s'),
            ],
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM categories');
    }
}
