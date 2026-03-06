<?php
// src/Command/ProducteurMaintenanceCommand.php
namespace App\Command;

use model\BDD;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:producteur:maintenance',
    description: 'Mets à jour/efface les producteurs selon les règles de résiliation/archivage.'
)]
class ProducteurMaintenanceCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = BDD::maintenanceProducteurs();
        $output->writeln("Maintenance exécutée, $count enregistrements affectés.");
        return Command::SUCCESS;
    }
}