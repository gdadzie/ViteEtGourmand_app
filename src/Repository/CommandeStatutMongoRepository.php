<?php

namespace Repository;

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;

class CommandeStatutMongoRepository
{
    private Collection $collection;

    public function __construct()
    {
        $mongoUrl = $_ENV['MONGODB_URI'];


        $client = new Client($mongoUrl);

        $db = $client->selectDatabase(
            $_ENV['MONGODB_DATABASE']
        );

        $this->collection = $db->selectCollection(
            'commande_statut_historique'
        );
    }
    /**
     * Ajoute un document d'historique pour une commande
     */
    public function ajouterHistorique(
        int $idCommande,
        string $ancienStatut,
        string $nouveauStatut,
        ?int $idUtilisateur = null,
        ?int $role = null
    ): void {

        $document = [
            'id_commande'       => $idCommande,
            'ancien_statut'     => $ancienStatut,
            'nouveau_statut'    => $nouveauStatut,
            'modifie_par'       => $idUtilisateur ?? 'Inconnu',
            'role'              => $role ?? 'Inconnu',
            'date_modification' => new UTCDateTime()
        ];

        $this->collection->insertOne($document);
    }

    /**
     * Récupère l'historique complet d'une commande
     */
    public function getHistoriqueParCommande(int $idCommande): array
    {
        $documents = $this->collection->find(
            ['id_commande' => $idCommande],
            ['sort' => ['date_modification' => 1]]
        );

        $historique = [];

        foreach ($documents as $doc) {

            $historique[] = [
                'date_modification' => isset($doc['date_modification'])
                    ? $doc['date_modification']->toDateTime()->format('Y-m-d H:i:s')
                    : 'Inconnu',

                'ancien_statut'  => $doc['ancien_statut'] ?? 'Inconnu',
                'nouveau_statut' => $doc['nouveau_statut'] ?? 'Inconnu',
                'modifie_par'    => $doc['modifie_par'] ?? 'Inconnu',
                'role'           => $doc['role'] ?? 'Inconnu',
            ];
        }

        return $historique;
    }

    /**
     * Supprime l'historique d'une commande
     */
    public function supprimerHistorique(int $idCommande): void
    {
        $this->collection->deleteMany([
            'id_commande' => $idCommande
        ]);
    }

    /**
     * Retourne la collection MongoDB
     */
    private function getCollection(): Collection
    {
        return $this->collection;
    }

    /**
     * Statistiques des menus
     */
    public function getStatsMenus(): array
    {
        $collection = $this->getCollection();

        $pipeline = [
            [
                '$group' => [
                    '_id'                => '$id_menu',
                    'nombre_commandes'  => ['$sum' => 1],
                    'chiffre_affaires'  => ['$sum' => '$prix_total']
                ]
            ],
            [
                '$sort' => [
                    'nombre_commandes' => -1
                ]
            ]
        ];

        return $collection->aggregate($pipeline)->toArray();
    }
}