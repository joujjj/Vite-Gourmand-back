<?php

namespace App\Command;

use App\Service\MongoService;
use Doctrine\ORM\EntityManagerInterface;
use MongoDB\BSON\UTCDateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:sync-mongo', description: 'Synchronise les commandes MySQL vers MongoDB')]
class SyncMongoCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private MongoService           $mongo,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commandes = $this->em->getRepository(\App\Entity\Commande::class)->findAll();
        $count = 0;

        foreach ($commandes as $commande) {
            $this->mongo->upsertCommande([
                'commande_id'      => $commande->getId(),
                'menu_id'          => $commande->getMenu()->getId(),
                'menu_titre'       => $commande->getMenu()->getTitre(),
                'prix_total'       => $commande->getPrixTotal(),
                'nombre_personnes' => $commande->getNombrePersonnes(),
                'statut'           => $commande->getStatut(),
                'date_prestation'  => new UTCDateTime(
                    $commande->getDatePrestation()->getTimestamp() * 1000
                ),
                'created_at'       => new UTCDateTime(
                    $commande->getCreatedAt()->getTimestamp() * 1000
                ),
            ]);
            $count++;
        }

        $output->writeln("✅ $count commandes synchronisées vers MongoDB.");
        return Command::SUCCESS;
    }
}