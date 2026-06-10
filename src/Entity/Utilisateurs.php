<?php
namespace Entity;

class Utilisateurs
{
    private ?int $id_utilisateur = null;
    private ?string $prenom;
    private ?string $nom;
    private ?string $email;
    private ?string $mot_de_passe;
    private ?string $telephone;

    private int $id_role;
    private bool $est_actif;
    private ?string $date_creation;

    // ✅ nouveaux champs
    private ?string $numero_rue;
    private ?string $nom_rue;
    private ?string $code_postal;
    private ?int $id_ville;

    // Constructeur complet
    public function __construct(
        ?string $prenom = null,
        ?string $nom = null,
        ?string $email = null,
        ?string $mot_de_passe = null,
        ?string $telephone = null,

        int $id_role = 1,
        bool $est_actif = true,
        ?string $date_creation = null,
        ?string $numero_rue = null,
        ?string $nom_rue = null,
        ?string $code_postal = null,
        ?int $id_ville = null
    ) {
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
        $this->mot_de_passe = $mot_de_passe;
        $this->telephone = $telephone;

        $this->id_role = $id_role;
        $this->est_actif = $est_actif;
        $this->date_creation = $date_creation ?? date('Y-m-d H:i:s');

        $this->numero_rue = $numero_rue;
        $this->nom_rue = $nom_rue;
        $this->code_postal = $code_postal;
        $this->id_ville = $id_ville;
    }

    // ===== GETTERS =====
    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }
    public function getPrenom(): ?string { return $this->prenom; }
    public function getNom(): ?string { return $this->nom; }
    public function getEmail(): ?string { return $this->email; }
    public function getMotDePasse(): ?string { return $this->mot_de_passe; }
    public function getTelephone(): ?string { return $this->telephone; }

    public function getIdRole(): int { return $this->id_role; }
    public function getEstActif(): bool { return $this->est_actif; }
    public function getDateCreation(): ?string { return $this->date_creation; }

    // nouveaux getters
    public function getNumeroRue(): ?string { return $this->numero_rue; }
    public function getNomRue(): ?string { return $this->nom_rue; }
    public function getCodePostal(): ?string { return $this->code_postal; }
    public function getIdVille(): ?int { return $this->id_ville; }

    // ===== SETTERS =====
    public function setIdUtilisateur(?int $id): void { $this->id_utilisateur = $id; }
    public function setPrenom(?string $prenom): void { $this->prenom = $prenom; }
    public function setNom(?string $nom): void { $this->nom = $nom; }
    public function setEmail(?string $email): void { $this->email = $email; }
    public function setMotDePasse(?string $mdp): void { $this->mot_de_passe = $mdp; }
    public function setTelephone(?string $telephone): void { $this->telephone = $telephone; }

    public function setIdRole(int $id_role): void { $this->id_role = $id_role; }
    public function setEstActif(bool $est_actif): void { $this->est_actif = $est_actif; }
    public function setDateCreation(?string $date_creation): void { $this->date_creation = $date_creation; }

    // nouveaux setters
    public function setNumeroRue(?string $numero_rue): void { $this->numero_rue = $numero_rue; }
    public function setNomRue(?string $nom_rue): void { $this->nom_rue = $nom_rue; }
    public function setCodePostal(?string $code_postal): void { $this->code_postal = $code_postal; }
    public function setIdVille(?int $id_ville): void { $this->id_ville = $id_ville; }
}