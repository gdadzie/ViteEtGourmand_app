<?php

namespace Repository;

use PDO;

class ContactRepository
{
    public function __construct(private PDO $connection)
    {
    }

    public function create(?int $userId, string $email, string $title, string $message): bool
    {
        $statement = $this->connection->prepare(
            'INSERT INTO messages_contact (id_utilisateur, email, titre_message, contenu_message, date_envoi)
             VALUES (:id_utilisateur, :email, :titre_message, :contenu_message, NOW())'
        );

        return $statement->execute([
            'id_utilisateur' => $userId,
            'email' => $email,
            'titre_message' => $title,
            'contenu_message' => $message,
        ]);
    }
}
