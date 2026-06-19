<?php

namespace Entity;

class Commande
{
    private int $id_commande, $id_utilisateur, $id_menu, $id_ville, $nombre_personnes;
    private float $prix_total, $frais_livraison, $reduction_appliquee, $pret_materiel;
    private string $adresse_livraison, $titreMenu;
    private ?string $date_livraison = null, $heure_livraison = null;
    private string $mode_reception, $mode_paiement, $statut_paiement;
    private string $statut, $date_creation;


    public function __construct(
        int $id_commande = 0,
        int $id_utilisateur = 0,
        int $id_menu = 0,

        int $id_ville = 0,
        int $nombre_personnes = 0,
        float $prix_total = 0.0,
        string $titreMenu = '',
        string $adresse_livraison = '',
        ?string $date_livraison = '',
        ?string $heure_livraison = '',
        string $mode_reception = 'livraison',
        string $mode_paiement = 'paiement_livraison',
        string $statut_paiement = 'unpaid',
        float $frais_livraison = 0.0,
        float $reduction_appliquee = 0.0,
        float $pret_materiel = 0.0,

        string $statut = 'en_attente',
        string $date_creation = ''
    ) {
        $this->id_commande = $id_commande;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_menu = $id_menu;
        $this->id_ville = $id_ville;
        $this->nombre_personnes = $nombre_personnes;
        $this->prix_total = $prix_total;
        $this->titreMenu = $titreMenu;
        $this->adresse_livraison = $adresse_livraison;
        $this->date_livraison = $date_livraison;
        $this->heure_livraison = $heure_livraison;
        $this->mode_reception = $mode_reception;
        $this->mode_paiement = $mode_paiement;
        $this->statut_paiement = $statut_paiement;
        $this->frais_livraison = $frais_livraison;
        $this->reduction_appliquee = $reduction_appliquee;
        $this->pret_materiel = $pret_materiel;
        $this->statut = $statut;
        $this->date_creation = $date_creation ?: date('Y-m-d H:i:s');
    }

    // GETTERS

    public function getIdCommande(): int { return $this->id_commande; }
    public function getIdUtilisateur(): int { return $this->id_utilisateur; }
    public function getIdMenu(): int { return $this->id_menu; }
    public function getIdVille(): int { return $this->id_ville; }
    public function getNombrePersonnes(): int { return $this->nombre_personnes; }
    public function getPrixTotal(): float { return $this->prix_total; }

    public function getTitreMenu(): string { return $this->titreMenu; }

    public function getAdresseLivraison(): string { return $this->adresse_livraison; }
    public function getDateLivraison(): ?string { return $this->date_livraison; }
    public function getHeureLivraison(): ?string { return $this->heure_livraison; }
    public function getModeReception(): string { return $this->mode_reception; }
    public function getModePaiement(): string { return $this->mode_paiement; }
    public function getStatutPaiement(): string { return $this->statut_paiement; }
    public function getFraisLivraison(): float { return $this->frais_livraison; }
    public function getReductionAppliquee(): float { return $this->reduction_appliquee; }
    public function getPretMateriel(): float { return $this->pret_materiel; }

    public function getStatut(): string { return $this->statut; }
    public function getDateCreation(): string { return $this->date_creation; }

    // SETTERS

    public function setIdCommande(int $id_commande): self { $this->id_commande = $id_commande; return $this; }
    public function setIdUtilisateur(int $id_utilisateur): self { $this->id_utilisateur = $id_utilisateur; return $this; }
    public function setIdMenu(int $id_menu): self { $this->id_menu = $id_menu; return $this; }
    public function setIdVille(int $id_ville): self { $this->id_ville = $id_ville; return $this; }
    public function setNombrePersonnes(int $nombre_personnes): self { $this->nombre_personnes = $nombre_personnes; return $this; }
    public function setPrixTotal(float $prix_total): self { $this->prix_total = $prix_total; return $this; }

    public function setTitreMenu(string $titreMenu): self { $this->titreMenu = $titreMenu; return $this; }
    public function setAdresseLivraison(string $adresse_livraison): self { $this->adresse_livraison = $adresse_livraison; return $this; }

    public function setDateLivraison(?string $date_livraison): self { $this->date_livraison = $date_livraison; return $this; }
    public function setHeureLivraison(?string $heure_livraison): self { $this->heure_livraison = $heure_livraison; return $this; }

    public function setModeReception(string $mode_reception): self { $this->mode_reception = $mode_reception; return $this; }
    public function setModePaiement(string $mode_paiement): self { $this->mode_paiement = $mode_paiement; return $this; }
    public function setStatutPaiement(string $statut_paiement): self { $this->statut_paiement = $statut_paiement; return $this; }

    public function setFraisLivraison(float $frais_livraison): self { $this->frais_livraison = $frais_livraison; return $this; }
    public function setReductionAppliquee(float $reduction_appliquee): self { $this->reduction_appliquee = $reduction_appliquee; return $this; }
    public function setPretMateriel(float $pret_materiel): self { $this->pret_materiel = $pret_materiel; return $this; }

    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }
    public function setDateCreation(string $date_creation): self { $this->date_creation = $date_creation; return $this; }
}