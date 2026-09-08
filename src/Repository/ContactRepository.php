<?php

namespace Repository;

use PDO;

class ContactRepository
{
    private ?bool $hasUserIdColumn = null;
    /** @var array<string, array<string, mixed>>|null */
    private ?array $columns = null;

    public function __construct(private PDO $connection)
    {
    }

    public function create(?int $userId, string $email, string $title, string $message): bool
    {
        $withUser = $this->hasUserIdColumn();
        $statement = $this->connection->prepare($withUser
            ? 'INSERT INTO messages_contact (id_utilisateur, email, titre_message, contenu_message, date_envoi)
               VALUES (:id_utilisateur, :email, :titre_message, :contenu_message, NOW())'
            : 'INSERT INTO messages_contact (email, titre_message, contenu_message, date_envoi)
               VALUES (:email, :titre_message, :contenu_message, NOW())'
        );

        $values = [
            'email' => $email,
            'titre_message' => $title,
            'contenu_message' => $message,
        ];
        if ($withUser) {
            $values['id_utilisateur'] = $userId;
        }

        return $statement->execute($values);
    }

    private function hasUserIdColumn(): bool
    {
        if ($this->hasUserIdColumn !== null) {
            return $this->hasUserIdColumn;
        }

        $statement = $this->connection->query("SHOW COLUMNS FROM messages_contact LIKE 'id_utilisateur'");
        return $this->hasUserIdColumn = $statement->fetchColumn() !== false;
    }

    /** @return array<int, array<string, mixed>> */
    public function readInbox(): array
    {
        $this->ensureManagementColumns();
        $primaryKey = $this->primaryKey();
        $statement = $this->connection->query(
            "SELECT `{$primaryKey}` AS id, email, titre_message, contenu_message, date_envoi,
                    est_traite, date_traitement, reponse
             FROM messages_contact
             ORDER BY est_traite ASC, date_envoi DESC"
        );

        return $statement->fetchAll();
    }

    /** @return array<string, mixed>|null */
    public function readInboxMessage(int $id): ?array
    {
        $this->ensureManagementColumns();
        $primaryKey = $this->primaryKey();
        $statement = $this->connection->prepare(
            "SELECT `{$primaryKey}` AS id, email, titre_message, contenu_message, date_envoi,
                    est_traite, date_traitement, reponse
             FROM messages_contact
             WHERE `{$primaryKey}` = :id"
        );
        $statement->execute(['id' => $id]);
        $message = $statement->fetch();

        return $message === false ? null : $message;
    }

    public function markAsTreated(int $id, ?string $reply = null): bool
    {
        $this->ensureManagementColumns();
        $primaryKey = $this->primaryKey();
        $statement = $this->connection->prepare(
            "UPDATE messages_contact
             SET est_traite = 1, date_traitement = NOW(), reponse = COALESCE(:reponse, reponse)
             WHERE `{$primaryKey}` = :id"
        );

        return $statement->execute(['id' => $id, 'reponse' => $reply]);
    }

    private function ensureManagementColumns(): void
    {
        $columns = $this->columns();
        $required = [
            'est_traite' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'date_traitement' => 'DATETIME NULL',
            'reponse' => 'TEXT NULL',
        ];

        foreach ($required as $name => $definition) {
            if (!isset($columns[$name])) {
                $this->connection->exec("ALTER TABLE messages_contact ADD COLUMN `{$name}` {$definition}");
            }
        }

        $this->columns = null;
    }

    /** @return array<string, array<string, mixed>> */
    private function columns(): array
    {
        if ($this->columns !== null) {
            return $this->columns;
        }

        $result = [];
        foreach ($this->connection->query('SHOW COLUMNS FROM messages_contact') as $column) {
            $result[$column['Field']] = $column;
        }

        return $this->columns = $result;
    }

    private function primaryKey(): string
    {
        foreach ($this->columns() as $name => $column) {
            if (($column['Key'] ?? '') === 'PRI') {
                return $name;
            }
        }

        throw new \RuntimeException('La table des messages de contact ne possède pas de clé primaire.');
    }
}
