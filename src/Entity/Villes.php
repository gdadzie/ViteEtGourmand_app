<?php

namespace Entity;

class Villes
{
    private int $idVille;

    private string $nomVille;

    private int $distanceKm;

    private float $prixBase;

    private bool $gratuit;

    // =====================================================
    // GETTERS
    // =====================================================

    public function getIdVille(): int
    {
        return $this->idVille;
    }

    public function getNomVille(): string
    {
        return $this->nomVille;
    }

    public function getDistanceKm(): int
    {
        return $this->distanceKm;
    }

    public function getPrixBase(): float
    {
        return $this->prixBase;
    }

    public function isGratuit(): bool
    {
        return $this->gratuit;
    }

    // =====================================================
    // SETTERS
    // =====================================================

    public function setIdVille(int $idVille): void
    {
        $this->idVille = $idVille;
    }

    public function setNomVille(string $nomVille): void
    {
        $this->nomVille = $nomVille;
    }

    public function setDistanceKm(int $distanceKm): void
    {
        $this->distanceKm = $distanceKm;
    }

    public function setPrixBase(float $prixBase): void
    {
        $this->prixBase = $prixBase;
    }

    public function setGratuit(bool $gratuit): void
    {
        $this->gratuit = $gratuit;
    }
}