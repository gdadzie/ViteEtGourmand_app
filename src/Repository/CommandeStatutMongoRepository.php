<?php

namespace Repository;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Collection;
use Throwable;

/**
 * Projection MongoDB des commandes pour l'historique et les statistiques.
 * MySQL demeure la source des données opérationnelles ; MongoDB sert aux analyses.
 */
class CommandeStatutMongoRepository
{
    private ?Collection $historyCollection = null;
    private ?Collection $analyticsCollection = null;
    private ?string $lastError = null;

    public function __construct()
    {
        try {
            $uri = $_ENV['MONGODB_URI'] ?? getenv('MONGODB_URI') ?: '';
            $database = $_ENV['MONGODB_DATABASE'] ?? getenv('MONGODB_DATABASE') ?: 'vite_et_gourmand';

            if ($uri === '') {
                throw new \RuntimeException('Configuration MongoDB absente.');
            }

            $client = new Client($uri, ['serverSelectionTimeoutMS' => 2000]);
            $db = $client->selectDatabase($database);
            $this->historyCollection = $db->selectCollection('commande_statut_historique');
            $this->analyticsCollection = $db->selectCollection('commande_analytics');
        } catch (Throwable $exception) {
            $this->lastError = $exception->getMessage();
        }
    }

    public function isAvailable(): bool
    {
        return $this->analyticsCollection !== null;
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function ajouterHistorique(
        int $idCommande,
        string $ancienStatut,
        string $nouveauStatut,
        ?int $idUtilisateur = null,
        ?int $role = null
    ): void {
        if ($this->historyCollection === null) {
            return;
        }

        try {
            $this->historyCollection->insertOne([
                'id_commande' => $idCommande,
                'ancien_statut' => $ancienStatut,
                'nouveau_statut' => $nouveauStatut,
                'modifie_par' => $idUtilisateur,
                'role' => $role,
                'date_modification' => new UTCDateTime(),
            ]);
        } catch (Throwable $exception) {
            $this->lastError = $exception->getMessage();
        }
    }

    /** @param array<int, array<string, mixed>> $commandes */
    public function synchroniserCommandes(array $commandes): bool
    {
        if ($this->analyticsCollection === null) {
            return false;
        }

        try {
            foreach ($commandes as $commande) {
                $this->analyticsCollection->updateOne(
                    ['id_commande' => (int) $commande['id_commande']],
                    ['$set' => [
                        'id_commande' => (int) $commande['id_commande'],
                        'id_menu' => (int) $commande['id_menu'],
                        'titre_menu' => (string) $commande['titre_menu'],
                        'prix_total' => (float) $commande['prix_total'],
                        'statut' => (string) $commande['statut'],
                        'date_creation' => (string) $commande['date_creation'],
                        'synchronise_le' => new UTCDateTime(),
                    ]],
                    ['upsert' => true]
                );
            }

            return true;
        } catch (Throwable $exception) {
            $this->lastError = $exception->getMessage();
            return false;
        }
    }

    /** @return array<int, array{menu_id:int, menu_titre:string, nombre_commandes:int, chiffre_affaires:float}> */
    public function getStatsMenus(array $filters = []): array
    {
        if ($this->analyticsCollection === null) {
            return [];
        }

        try {
            $pipeline = [];
            $match = $this->buildAnalyticsMatch($filters);
            if ($match !== []) {
                $pipeline[] = ['$match' => $match];
            }

            $pipeline = array_merge($pipeline, [
                ['$group' => [
                    '_id' => ['id_menu' => '$id_menu', 'titre_menu' => '$titre_menu'],
                    'nombre_commandes' => ['$sum' => 1],
                    'chiffre_affaires' => ['$sum' => '$prix_total'],
                ]],
                ['$sort' => ['nombre_commandes' => -1, 'chiffre_affaires' => -1]],
            ]);

            $stats = [];
            foreach ($this->analyticsCollection->aggregate($pipeline) as $document) {
                $stats[] = [
                    'menu_id' => (int) ($document['_id']['id_menu'] ?? 0),
                    'menu_titre' => (string) ($document['_id']['titre_menu'] ?? 'Menu sans nom'),
                    'nombre_commandes' => (int) ($document['nombre_commandes'] ?? 0),
                    'chiffre_affaires' => (float) ($document['chiffre_affaires'] ?? 0),
                ];
            }

            return $stats;
        } catch (Throwable $exception) {
            $this->lastError = $exception->getMessage();
            return [];
        }
    }

    /** @return array{total:int,en_attente:int,acceptees:int,terminees:int,chiffre_affaires:float} */
    public function getStatsGlobales(array $filters = []): array
    {
        $result = ['total' => 0, 'en_attente' => 0, 'acceptees' => 0, 'terminees' => 0, 'chiffre_affaires' => 0.0];

        if ($this->analyticsCollection === null) {
            return $result;
        }

        try {
            $pipeline = [];
            $match = $this->buildAnalyticsMatch($filters);
            if ($match !== []) {
                $pipeline[] = ['$match' => $match];
            }
            $pipeline[] = ['$group' => [
                '_id' => '$statut',
                'total' => ['$sum' => 1],
                'chiffre_affaires' => ['$sum' => '$prix_total'],
            ]];

            foreach ($this->analyticsCollection->aggregate($pipeline) as $document) {
                $statut = (string) ($document['_id'] ?? '');
                $total = (int) ($document['total'] ?? 0);
                $result['total'] += $total;
                $result['chiffre_affaires'] += (float) ($document['chiffre_affaires'] ?? 0);

                if (in_array($statut, ['recue', 'en_attente'], true)) $result['en_attente'] += $total;
                if (in_array($statut, ['acceptee', 'payee', 'en_preparation'], true)) $result['acceptees'] += $total;
                if (in_array($statut, ['livree', 'terminee'], true)) $result['terminees'] += $total;
            }
        } catch (Throwable $exception) {
            $this->lastError = $exception->getMessage();
        }

        return $result;
    }

    public function supprimerHistorique(int $idCommande): void
    {
        try {
            $this->historyCollection?->deleteMany(['id_commande' => $idCommande]);
            $this->analyticsCollection?->deleteOne(['id_commande' => $idCommande]);
        } catch (Throwable $exception) {
            $this->lastError = $exception->getMessage();
        }
    }

    public function getHistoriqueParCommande(int $idCommande): array
    {
        if ($this->historyCollection === null) {
            return [];
        }

        try {
            $historique = [];
            foreach ($this->historyCollection->find(['id_commande' => $idCommande], ['sort' => ['date_modification' => 1]]) as $doc) {
                $historique[] = [
                    'date_modification' => isset($doc['date_modification']) ? $doc['date_modification']->toDateTime()->format('Y-m-d H:i:s') : 'Inconnu',
                    'ancien_statut' => $doc['ancien_statut'] ?? 'Inconnu',
                    'nouveau_statut' => $doc['nouveau_statut'] ?? 'Inconnu',
                    'modifie_par' => $doc['modifie_par'] ?? 'Inconnu',
                    'role' => $doc['role'] ?? 'Inconnu',
                ];
            }
            return $historique;
        } catch (Throwable $exception) {
            $this->lastError = $exception->getMessage();
            return [];
        }
    }

    /** @return array<string, mixed> */
    private function buildAnalyticsMatch(array $filters): array
    {
        $match = [];
        if (!empty($filters['id_menu'])) {
            $match['id_menu'] = (int) $filters['id_menu'];
        }

        $dateRange = [];
        if (!empty($filters['date_debut'])) {
            $dateRange['$gte'] = $filters['date_debut'] . ' 00:00:00';
        }
        if (!empty($filters['date_fin'])) {
            $dateRange['$lte'] = $filters['date_fin'] . ' 23:59:59';
        }
        if ($dateRange !== []) {
            $match['date_creation'] = $dateRange;
        }

        return $match;
    }
}
