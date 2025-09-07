<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin-user',
    description: 'Crée un compte admin admin@ecoride.com avec un mot de passe donné',
)]
class CreateAdmin extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe pour le compte admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $password = $input->getArgument('password');

        // Vérifier si l'admin existe déjà
        $existingAdmin = $this->userRepository->findOneBy(['email' => 'admin@ecoride.com']);
        if ($existingAdmin) {
            $io->error('Un utilisateur avec l’email admin@ecoride.com existe déjà.');
            return Command::FAILURE;
        }

        // Créer le nouvel admin
        $admin = new User();
        $admin->setEmail('admin@ecoride.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstName('Admin');
        $admin->setLastName('Ecoride');
        $admin->setAdress('Adresse par défaut');
        $admin->setCredits(0);

        // Hacher le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($admin, $password);
        $admin->setPassword($hashedPassword);

        $this->em->persist($admin);
        $this->em->flush();

        $io->success('Le compte admin@ecoride.com a été créé avec succès.');

        return Command::SUCCESS;
    }
}
