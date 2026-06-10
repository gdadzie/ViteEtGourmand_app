<?php
declare(strict_types=1);

namespace Repository;

use Entity\Horaires;
use PDO;


class HorairesRepository
{
    private PDO $conn;

    // ✅ LE SEUL CONSTRUCTEUR VALIDE
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }
    public function readAll(): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM horaires");
        $stmt->execute();

        $horaires = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $horaires[] = new Horaires(
                (int)$data['id_horaire'],
                $data['jour'],
                $data['heure_ouverture'],
                $data['heure_fermeture'],
                (bool)$data['est_ferme']
            );
        }

        return $horaires;
    }

    public function readById(int $id): ?Horaires
    {
        $stmt = $this->conn->prepare("SELECT * FROM horaires WHERE id_horaire = :id");
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new Horaires(
            (int)$data['id_horaire'],
            $data['jour'],
            $data['heure_ouverture'],
            $data['heure_fermeture'],
            (bool)$data['est_ferme']
        );
    }

    public function update(Horaires $horaire): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE horaires 
         SET heure_ouverture = :ouverture,
             heure_fermeture = :fermeture,
             est_ferme = :est_ferme
         WHERE id_horaire = :id"
        );

        return $stmt->execute([
            'ouverture' => $horaire->getHeureOuverture(),
            'fermeture' => $horaire->getHeureFermeture(),
            'est_ferme' => (int)$horaire->getEstFerme(),
            'id' => $horaire->getIdHoraire(),
        ]);
    }

}
