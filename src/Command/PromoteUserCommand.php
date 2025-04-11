<?php 

namespace App\Command;

use App\Entity\Dresseur;
use App\Entity\User;
use App\Repository\DresseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;



// Les attributs de la commande
#[AsCommand(
    name: 'app:promote-user',
    description: 'Attribue un rôle admin à un utilisateur'
)]

// Définition de la commande
class PromoteUserCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'nom d\'utilisateur')
            ->addArgument('role', InputArgument::REQUIRED, 'Rôle à attribuer');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
        $username = $input->getArgument('username');
        $role = $input->getArgument('role');

        $user = $this->em->getRepository(Dresseur::class)
            ->findOneBy(['username' => $username]);

        if (!$user) {
            $output->writeln('<error>Utilisateur non trouvé</error>');
            return Command::FAILURE;
        }

        $user->addRole($role);
        $this->em->flush();

        $output->writeln('<info>Rôle '.$role.' attribué avec succès à '.$username.'</info>');
        return Command::SUCCESS;
    
    }
}
 ?>