<?php

namespace Entity;

class Plats
{
    private ?int $id_plat = null;
    private ?string $nom_plat = null;
    private ?string $type_plat = null;
    private ?int $id_menu = null;
    private ?string $image_plat = null;

    // ===== GETTERS =====

    public function getIdPlat(): ?int
    {
        return $this->id_plat;
    }

    public function getNomPlat(): ?string
    {
        return $this->nom_plat;
    }

    public function getTypePlat(): ?string
    {
        return $this->type_plat;
    }

    public function getIdMenu(): ?int
    {
        return $this->id_menu;
    }

    public function getImagePlat(): ?string
    {
        return $this->image_plat;
    }

    // ===== SETTERS =====

    public function setIdPlat(int $id_plat): self
    {
        $this->id_plat = $id_plat;
        return $this;
    }

    public function setNomPlat(string $nom_plat): self
    {
        $this->nom_plat = $nom_plat;
        return $this;
    }

    public function setTypePlat(string $type_plat): self
    {
        $typesAutorises = ['entree', 'plat', 'dessert'];

        if (!in_array($type_plat, $typesAutorises, true)) {
            throw new \InvalidArgumentException('Type de plat invalide');
        }

        $this->type_plat = $type_plat;
        return $this;
    }

    public function setIdMenu(int $id_menu): self
    {
        $this->id_menu = $id_menu;
        return $this;
    }

    public function setImagePlat(string $image_plat): self
    {
        $this->image_plat = $image_plat;
        return $this;
    }
}