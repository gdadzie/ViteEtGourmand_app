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

    public function create(?int $userId, string $email, string $title, string $message, string $channel = 'contact'): bool
    {
        $this->ensureManagementColumns();
        $withUser = $this->hasUserIdColumn();
        $statement = $this->connection->prepare($withUser
            ? 'INSERT INTO messages_contact (id_utilisateur, email, titre_message, contenu_message, canal, date_envoi)
               VALUES (:id_utilisateur, :email, :titre_message, :contenu_message, :canal, NOW())'
            : 'INSERT INTO messages_contact (email, titre_message, contenu_message, canal, date_envoi)
               VALUES (:email, :titre_message, :contenu_message, :canal, NOW())'
        );

        $values = [
            'email' => $email,
            'titre_message' => $title,
            'contenu_message' => $message,
            'canal' => $channel,
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
            "SELECT `{$primaryKey}` AS id, email, titre_message, contenu_message, canal, date_envoi,
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
            "SELECT `{$primaryKey}` AS id, email, titre_message, contenu_message, canal, date_envoi,
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

    public function markAsPending(int $id): bool
    {
        $this->ensureManagementColumns();
        $primaryKey = $this->primaryKey();
        $statement = $this->connection->prepare(
            "UPDATE messages_contact
             SET est_traite = 0, date_traitement = NULL
             WHERE `{$primaryKey}` = :id"
        );

        return $statement->execute(['id' => $id]);
    }

    /** @return array<int, array<string, mixed>> */
    public function readMessagesForEmail(string $email): array
    {
        $this->ensureManagementColumns();
        $primaryKey = $this->primaryKey();
        $statement = $this->connection->prepare(
            "SELECT `{$primaryKey}` AS id, email, titre_message, contenu_message, canal, date_envoi,
                    est_traite, date_traitement, reponse
             FROM messages_contact
             WHERE email = :email
             ORDER BY date_envoi DESC"
        );
        $statement->execute(['email' => $email]);

        return $statement->fetchAll();
    }

    /** @return array<int, array<string, mixed>> */
    public function readConversation(int $messageId): array
    {
        $this->ensureManagementColumns();
        $statement = $this->connection->prepare(
            'SELECT auteur, contenu, date_envoi
             FROM messages_contact_echanges
             WHERE message_contact_id = :message_id
             ORDER BY date_envoi ASC, id ASC'
        );
        $statement->execute(['message_id' => $messageId]);

        return $statement->fetchAll();
    }

    public function addExchange(int $messageId, string $author, string $content): bool
    {
        $this->ensureManagementColumns();
        $statement = $this->connection->prepare(
            'INSERT INTO messages_contact_echanges (message_contact_id, auteur, contenu, date_envoi)
             VALUES (:message_id, :auteur, :contenu, NOW())'
        );

        return $statement->execute([
            'message_id' => $messageId,
            'auteur' => $author,
            'contenu' => $content,
        ]);
    }

    private function ensureManagementColumns(): void
    {
        $columns = $this->columns();
        $required = [
            'est_traite' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'date_traitement' => 'DATETIME NULL',
            'reponse' => 'TEXT NULL',
            'canal' => "VARCHAR(20) NOT NULL DEFAULT 'contact'",
        ];

        foreach ($required as $name => $definition) {
            if (!isset($columns[$name])) {
                $this->connection->exec("ALTER TABLE messages_contact ADD COLUMN `{$name}` {$definition}");
            }
        }

        $this->connection->exec(
            'CREATE TABLE IF NOT EXISTS messages_contact_echanges (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                message_contact_id INT NOT NULL,
                auteur VARCHAR(20) NOT NULL,
                contenu TEXT NOT NULL,
                date_envoi DATETIME NOT NULL,
                INDEX idx_contact_exchange (message_contact_id, date_envoi)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );

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
