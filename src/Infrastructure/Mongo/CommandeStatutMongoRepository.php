<?php

namespace Infrastructure\Mongo;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
class CommandeStatutMongoRepository
{
    private $collection;

    public function __construct()
    {
        $client = MongoClientFactory::getClient();
        $db = $client->vite_et_gourmand;
        $this->collection = $db->commande_statuts;
    }

    public function ajouterHistorique(
        int $commandeId,
        string $statut,
        int $userId,
        string $source = 'system'
    ): void {
        $this->collection->insertOne([
            'commande_id' => $commandeId,
            'statut'      => $statut,
            'user_id'     => $userId,
            'source'      => $source,
            'date'        => new UTCDateTime()
        ]);
    }

    public function getHistoriqueParCommande(int $commandeId): array
    {
        return $this->collection
            ->find(
                ['commande_id' => $commandeId],
                ['sort' => ['date' => 1]]
            )
            ->toArray();
    }
}
