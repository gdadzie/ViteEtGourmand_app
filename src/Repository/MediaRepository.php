<?php
declare(strict_types=1);

namespace Repository;

use PDO;

final class MediaRepository
{
    private bool $tableReady = false;

    public function __construct(private PDO $conn) {}

    public function save(string $resourceType, int $resourceId, string $filename, string $mimeType, string $content): void
    {
        $this->ensureTable();
        $stmt = $this->conn->prepare(
            'INSERT INTO media_images (resource_type, resource_id, filename, mime_type, content)
             VALUES (:type, :id, :filename, :mime, :content)
             ON DUPLICATE KEY UPDATE filename = VALUES(filename), mime_type = VALUES(mime_type), content = VALUES(content), updated_at = CURRENT_TIMESTAMP'
        );
        $stmt->bindValue(':type', $resourceType);
        $stmt->bindValue(':id', $resourceId, PDO::PARAM_INT);
        $stmt->bindValue(':filename', $filename);
        $stmt->bindValue(':mime', $mimeType);
        $stmt->bindValue(':content', $content, PDO::PARAM_LOB);
        $stmt->execute();
    }

    /** @return array{mime_type: string, content: string}|null */
    public function find(string $resourceType, int $resourceId): ?array
    {
        $this->ensureTable();
        $stmt = $this->conn->prepare(
            'SELECT mime_type, content FROM media_images WHERE resource_type = :type AND resource_id = :id'
        );
        $stmt->execute(['type' => $resourceType, 'id' => $resourceId]);
        $media = $stmt->fetch(PDO::FETCH_ASSOC);

        return $media ?: null;
    }

    public function delete(string $resourceType, int $resourceId): void
    {
        $this->ensureTable();
        $stmt = $this->conn->prepare('DELETE FROM media_images WHERE resource_type = :type AND resource_id = :id');
        $stmt->execute(['type' => $resourceType, 'id' => $resourceId]);
    }

    private function ensureTable(): void
    {
        if ($this->tableReady) return;

        $this->conn->exec(
            'CREATE TABLE IF NOT EXISTS media_images (
                id_media INT AUTO_INCREMENT PRIMARY KEY,
                resource_type VARCHAR(20) NOT NULL,
                resource_id INT NOT NULL,
                filename VARCHAR(255) NOT NULL,
                mime_type VARCHAR(100) NOT NULL,
                content LONGBLOB NOT NULL,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY uniq_media_resource (resource_type, resource_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
        $this->tableReady = true;
    }
}
