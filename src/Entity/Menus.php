<?php

namespace Entity;

class Menus
{
    private int $id_menu;
    private string $titre;
    private string $description;
    private ?string $image = null; // nullable
    private string $theme;
    private string $regime;
    private int $nb_min_personne;
    private float $prix_par_personne;
    private string $conditions;
    private int $stock_disponible;
    private string $date_creation;
    private string $date_modification;

    /**
     * @var Plats[]
     */
    private array $plats = [];

    /**
     * Constructor
     */
    public function __construct(
        int $id_menu = 0,
        string $titre = '',
        string $description = '',
        ?string $image = null, // nullable
        string $theme = '',
        string $regime = '',
        int $nb_min_personne = 0,
        float $prix_par_personne = 0.0,
        string $conditions = '',
        int $stock_disponible = 0,
        string $date_creation = '',
        string $date_modification = ''
    ) {
        $this->id_menu = $id_menu;
        $this->titre = $titre;
        $this->description = $description;
        $this->image = $image;
        $this->theme = $theme;
        $this->regime = $regime;
        $this->nb_min_personne = $nb_min_personne;
        $this->prix_par_personne = $prix_par_personne;
        $this->conditions = $conditions;
        $this->stock_disponible = $stock_disponible;
        $this->date_creation = $date_creation;
        $this->date_modification = $date_modification;
    }

    // =====================
    // GETTERS
    // =====================
    public function getIdMenu(): int { return $this->id_menu; }
    public function getTitre(): string { return $this->titre; }
    public function getDescription(): string { return $this->description; }
    public function getImage(): ?string { return $this->image; } // nullable
    public function getTheme(): string { return $this->theme; }
    public function getRegime(): string { return $this->regime; }
    public function getNbMinPersonne(): int { return $this->nb_min_personne; }
    public function getPrixParPersonne(): float { return $this->prix_par_personne; }
    public function getConditions(): string { return $this->conditions; }
    public function getStockDisponible(): int { return $this->stock_disponible; }
    public function getDateCreation(): string { return $this->date_creation; }
    public function getDateModification(): string { return $this->date_modification; }

    public function getPlats(): array { return $this->plats; }

    public function getPlatsApercu(int $limit = 3): array
    {
        return array_slice($this->plats, 0, $limit);
    }

    // =====================
    // SETTERS
    // =====================
    public function setIdMenu(int $id_menu): self
    {
        $this->id_menu = $id_menu;
        return $this;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setImage(?string $image): self // nullable setter
    {
        $this->image = $image;
        return $this;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;
        return $this;
    }

    public function setRegime(string $regime): self
    {
        $this->regime = $regime;
        return $this;
    }

    public function setNbMinPersonne(int $nb_min_personne): self
    {
        $this->nb_min_personne = $nb_min_personne;
        return $this;
    }

    public function setPrixParPersonne(float $prix_par_personne): self
    {
        $this->prix_par_personne = $prix_par_personne;
        return $this;
    }

    public function setConditions(string $conditions): self
    {
        $this->conditions = $conditions;
        return $this;
    }

    public function setStockDisponible(int $stock_disponible): self
    {
        $this->stock_disponible = $stock_disponible;
        return $this;
    }

    public function setDateCreation(string $date_creation): self
    {
        $this->date_creation = $date_creation;
        return $this;
    }

    public function setDateModification(string $date_modification): self
    {
        $this->date_modification = $date_modification;
        return $this;
    }

    // =====================
    // SETTERS MÉTIER
    // =====================
    public function addPlat(Plats $plat): self
    {
        $this->plats[] = $plat;
        return $this;
    }

    public function setPlats(array $plats): self
    {
        $this->plats = $plats;
        return $this;
    }


    public function getImagePath(): string
    {
        if (
            $this->image &&
            file_exists('uploads/' . $this->image)
        ) {
            return 'uploads/' . $this->image;
        }

        return 'uploads/default.png';
    }


}
