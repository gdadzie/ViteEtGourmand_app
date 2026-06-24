<?php

namespace Entity;

class Avis
{
    private int $id_avis;

    private int $id_utilisateur;

    private int $id_commande;

    private int $note;

    private string $commentaire;

    private bool $est_valide = false;
    private string $dateCreation;

    private string $nom_utilisateur;

    // =========================
    // GETTERS
    // =========================

    public function getIdAvis(): int
    {
        return $this->id_avis;
    }

    public function getIdUtilisateur(): int
    {
        return $this->id_utilisateur;
    }

    public function getIdCommande(): int
    {
        return $this->id_commande;
    }

    public function getNote(): int
    {
        return $this->note;
    }

    public function getCommentaire(): string
    {
        return $this->commentaire;
    }

    public function getEstValide(): bool
    {
        return $this->est_valide;
    }

    public function getDateCreation(): string
    {
        return $this->dateCreation;
    }
     public function getNomUtilisateur(): string
     {
         return $this->nom_utilisateur;
     }

    // =========================
    // SETTERS
    // =========================

    public function setIdAvis(int $id_avis): self
    {
        $this->id_avis = $id_avis;
        return $this;
    }

    public function setIdUtilisateur(int $id_utilisateur): self
    {
        $this->id_utilisateur = $id_utilisateur;
        return $this;
    }

    public function setIdCommande(int $id_commande): self
    {
        $this->id_commande = $id_commande;
        return $this;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function setEstValide(bool $est_valide): self
    {
        $this->est_valide = $est_valide;
        return $this;
    }

    public function setDateCreation(string $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function setNomUtilisateur(string $nom_utilisateur): void
    {
        $this->nom_utilisateur = $nom_utilisateur;
    }
}