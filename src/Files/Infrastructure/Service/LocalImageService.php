<?php

declare(strict_types=1);

namespace App\Files\Infrastructure\Service;

use App\Files\Application\Service\ImageServiceInterface;
use App\Files\Domain\Entity\ImageFile;
use App\Files\Domain\Exception\FailedToCreateUploadDirectoryException;
use App\Files\Domain\Exception\FailedToMoveUploadedFileException;
use App\Files\Domain\Exception\FileSizeTooLargeException;
use App\Files\Domain\Exception\FileWasNotUploadedException;
use App\Files\Domain\Exception\UnsupportedMimeTypeException;

final readonly class LocalImageService implements ImageServiceInterface
{
    private string $uploadDirectory;
    private string $publicPath;
    private const array ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    private const int MAX_FILE_SIZE = 5242880; // 5MB

    public function __construct(string $uploadDirectory, string $publicPath)
    {
        $this->uploadDirectory = rtrim($uploadDirectory, '/');
        $this->publicPath = rtrim($publicPath, '/');
    }

    public function upload(
        string $tmpName,
        string $name,
        string $type,
        int $size,
    ): ImageFile {
        if (!is_uploaded_file($tmpName)) {
            throw FileWasNotUploadedException::viaHTTP();
        }

        if (!in_array($type, self::ALLOWED_MIME_TYPES, true)) {
            throw new UnsupportedMimeTypeException($type);
        }

        if ($size > self::MAX_FILE_SIZE) {
            throw new FileSizeTooLargeException;
        }

        $extension = $this->getExtensionFromMimeType($type);
        $filename = $this->generateUniqueFilename() . '.' . $extension;
        $relativePath = 'images/' . date('Y/m');
        $fullPath = $this->uploadDirectory . '/' . $relativePath;

        if (!is_dir($fullPath)) {
            if (!mkdir($fullPath, 0755, true)) {
                throw new FailedToCreateUploadDirectoryException;
            }
        }

        $destination = $fullPath . '/' . $filename;

        if (!move_uploaded_file($tmpName, $destination)) {
            throw new FailedToMoveUploadedFileException;
        }

        $imageSize = getimagesize($destination);

        return new ImageFile(
            filename: $filename,
            path: $relativePath,
            mimeType: $type,
            size: $size,
            width: $imageSize[0],
            height: $imageSize[1],
        );
    }

    public function delete(ImageFile $image): void
    {
        $fullPath = $this->uploadDirectory . '/' . $image->fullPath();

        if (file_exists(filename: $fullPath)) {
            unlink(filename: $fullPath);
        }
    }

    public function exists(ImageFile $imageFile): bool
    {
        return file_exists(filename: $this->uploadDirectory . '/' . $imageFile->fullPath());
    }

    private static function generateUniqueFilename(): string
    {
        return uniqid('img_', true);
    }

    private function getExtensionFromMimeType(string $mimeType): string
    {
        return match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            default => throw new UnsupportedMimeTypeException($mimeType),
        };
    }
}
