<?php
declare(strict_types=1);

namespace Repository;

use PDO;

final class PasswordResetRepository
{
    public function __construct(private PDO $connection) {}

    public function create(int $userId, string $tokenHash): void
    {
        $this->connection->prepare('DELETE FROM password_reset_tokens WHERE id_utilisateur = :user_id OR expires_at < NOW()')
            ->execute(['user_id' => $userId]);
        $this->connection->prepare('INSERT INTO password_reset_tokens (id_utilisateur, token_hash, expires_at) VALUES (:user_id, :token_hash, DATE_ADD(NOW(), INTERVAL 1 HOUR))')
            ->execute(['user_id' => $userId, 'token_hash' => $tokenHash]);
    }

    public function consume(string $tokenHash): ?int
    {
        $this->connection->beginTransaction();
        try {
            $statement = $this->connection->prepare('SELECT id, id_utilisateur FROM password_reset_tokens WHERE token_hash = :token_hash AND used_at IS NULL AND expires_at > NOW() FOR UPDATE');
            $statement->execute(['token_hash' => $tokenHash]);
            $token = $statement->fetch(PDO::FETCH_ASSOC);
            if (!$token) {
                $this->connection->rollBack();
                return null;
            }
            $this->connection->prepare('UPDATE password_reset_tokens SET used_at = NOW() WHERE id = :id')->execute(['id' => $token['id']]);
            $this->connection->commit();
            return (int) $token['id_utilisateur'];
        } catch (\Throwable $exception) {
            if ($this->connection->inTransaction()) $this->connection->rollBack();
            throw $exception;
        }
    }
}
