<?php

declare(strict_types=1);

namespace App\Files\Infrastructure\Repository\Write;

use App\Files\Domain\Entity\Image;
use App\Files\Domain\Repository\ImageRepositoryInterface;
use Doctrine\DBAL\Connection;

final readonly class DoctrineImageRepository implements ImageRepositoryInterface
{
    public function __construct(
        private Connection $connection
    ) {}

    public function save(Image $image): void
    {
        $data = [
            'id' => $image->id()->value(),
            'filename' => $image->file()->filename(),
            'path' => $image->file()->path(),
            'mime_type' => $image->file()->mimeType(),
            'size' => $image->file()->size(),
            'width' => $image->file()->width(),
            'height' => $image->file()->height(),
            'owner_id' => $image->ownerId()->value(),
            'uploaded_at' => $image->uploadedAt()->toDateTimeString(),
        ];

        $exists = $this->connection->fetchOne(
            query: '
                SELECT COUNT(*)
                FROM images
                WHERE id = :id
            ',
            params: [
                'id' => $image->id()->value(),
            ],
        );

        if ($exists === false) {
            $this->connection->insert(
                table: 'images',
                data: $data,
            );
        } else {
            $this->connection->update(
                table: 'images',
                data: $data,
                criteria: [
                    'id' => $image->id()->value(),
                ],
            );
        }
    }
}
