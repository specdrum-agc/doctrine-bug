<?php

namespace App\Command;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test',
    description: 'Add a short description for your command',
)]
class TestCommand extends Command
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
    }

    protected function configure(): void
    {
        $this
            ->addOption('restart', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('restart')) {
            $this->createRole();
            $io->success('Saved new role');
            return Command::SUCCESS;
        }

        $this->test();
        $io->success('Success!');

        return Command::SUCCESS;
    }

    private function createRole()
    {
        $entityManager = $this->doctrine->getManager();

        $role = new Role();
        $role->setId(1);
        $role->setName('test');

        $user = $this->doctrine->getRepository(User::class)->find(1);
        $user->addRole($role);

        $entityManager->persist($role);
        $entityManager->flush();
    }

    private function test()
    {
        $entityManager = $this->doctrine->getManager();

        $role = $this->doctrine->getRepository(Role::class)->find(1);
        $user = $role->getUsers()->current();
        $user->setName('test');

        // Need for collection initialization
        $roles = $user->getRoles()->toArray();
        $entityManager->remove($role);
        $entityManager->flush();

        // Second flush fails
        $entityManager->flush();
    }
}
