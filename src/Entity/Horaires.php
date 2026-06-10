<?php
declare(strict_types=1);

namespace Entity;

class Horaires
{
    private int $id_horaire;
    private string $jour;
    private ?string $heure_ouverture;   // nullable
    private ?string $heure_fermeture;   // nullable
    private bool $est_ferme;

    public function __construct(
        int $id_horaire,
        string $jour,
        ?string $heure_ouverture,
        ?string $heure_fermeture,
        bool $est_ferme
    ) {
        $this->id_horaire = $id_horaire;
        $this->jour = $jour;
        $this->heure_ouverture = $heure_ouverture;
        $this->heure_fermeture = $heure_fermeture;
        $this->est_ferme = $est_ferme;
    }

    // ======================
    // GETTERS
    // ======================
    public function getIdHoraire(): int
    {
        return $this->id_horaire;
    }

    public function getJour(): string
    {
        return $this->jour;
    }

    public function getHeureOuverture(): ?string
    {
        return $this->heure_ouverture;
    }

    public function getHeureFermeture(): ?string
    {
        return $this->heure_fermeture;
    }

    public function getEstFerme(): bool
    {
        return $this->est_ferme;
    }

    // ======================
    // SETTERS
    // ======================
    public function setJour(string $jour): void
    {
        $this->jour = $jour;
    }

    public function setHeureOuverture(?string $heure): void
    {
        $this->heure_ouverture = $heure;
    }

    public function setHeureFermeture(?string $heure): void
    {
        $this->heure_fermeture = $heure;
    }

    public function setEstFerme(bool $est_ferme): void
    {
        $this->est_ferme = $est_ferme;
    }
}
