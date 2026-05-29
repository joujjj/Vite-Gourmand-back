<?php

namespace App\Service;

use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;

class MongoService
{
    private Collection $collection;

    public function __construct(string $mongoUri, string $mongoDB)
    {
        $client = new Client($mongoUri);
        $this->collection = $client
            ->selectDatabase($mongoDB)
            ->selectCollection('commandes_stats');
    }

    /**
     * Insère ou met à jour le document de stats d'une commande dans MongoDB.
     *
     * Document stocké :
     * {
     *   commande_id      : int,
     *   menu_id          : int,
     *   menu_titre       : string,
     *   prix_total       : float,
     *   nombre_personnes : int,
     *   statut           : string,
     *   date_prestation  : UTCDateTime,
     *   created_at       : UTCDateTime
     * }
     */
    public function upsertCommande(array $data): void
    {
        $this->collection->updateOne(
            ['commande_id' => $data['commande_id']],
            ['$set' => $data],
            ['upsert' => true]
        );
    }

    /**
     * Retourne les statistiques agrégées par menu depuis MongoDB.
     *
     * Filtres optionnels :
     *   - menu_id    (int)
     *   - date_debut (\DateTime)
     *   - date_fin   (\DateTime)
     */
    public function getStats(array $filters = []): array
    {
        $match = ['statut' => ['$ne' => 'annulee']];

        if (!empty($filters['menu_id'])) {
            $match['menu_id'] = (int) $filters['menu_id'];
        }
        if (!empty($filters['date_debut'])) {
            $match['date_prestation']['$gte'] = new UTCDateTime(
                $filters['date_debut']->getTimestamp() * 1000
            );
        }
        if (!empty($filters['date_fin'])) {
            $match['date_prestation']['$lte'] = new UTCDateTime(
                $filters['date_fin']->getTimestamp() * 1000
            );
        }

        $pipeline = [
            ['$match' => $match],
            ['$group' => [
                '_id'              => '$menu_id',
                'menu_titre'       => ['$first' => '$menu_titre'],
                'nb_commandes'     => ['$sum' => 1],
                'chiffre_affaires' => ['$sum' => '$prix_total'],
            ]],
            ['$sort' => ['nb_commandes' => -1]],
        ];

        $results = $this->collection->aggregate($pipeline)->toArray();

        $parMenu = array_map(fn($r) => [
            'menu_id'          => $r['_id'],
            'menu_titre'       => $r['menu_titre'],
            'nb_commandes'     => $r['nb_commandes'],
            'chiffre_affaires' => round($r['chiffre_affaires'], 2),
        ], $results);

        return [
            'total_commandes'  => array_sum(array_column($parMenu, 'nb_commandes')),
            'chiffre_affaires' => round(array_sum(array_column($parMenu, 'chiffre_affaires')), 2),
            'par_menu'         => $parMenu,
        ];
    }
}
