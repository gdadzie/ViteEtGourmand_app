<?php

namespace Repository;

use PDO;

class ContactRepository
{
    private ?bool $hasUserIdColumn = null;

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
}
