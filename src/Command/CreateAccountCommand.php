<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Question\Question;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateAccountCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    protected static $defaultDescription = 'Add user account';

    private string $plainPassword;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'E-mail address')
            ->addArgument('firstName', InputArgument::REQUIRED, 'First name')
            ->addArgument('lastName', InputArgument::REQUIRED, 'Last name')
            ->addArgument('cnp', InputArgument::OPTIONAL, 'CNP')
            ->addOption(
                'role',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'User role',
                ['ROLE_ADMIN']
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('Please enter the user\'s password:');
        $question->setHidden(true);
        $this->plainPassword = $helper->ask($input, $output, $question);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $firstName = $input->getArgument('firstName');
        $lastName = $input->getArgument('lastName');
        $cnp = $input->getArgument('cnp');
        $roles = $input->getOption('role');

        $user = new User();
        $user->email = $email;
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->cnp = $cnp ? $cnp : '';
        $user->setRoles($roles);
        $user->setPassword($this->plainPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);

        $io->success('You have created an user');

        return self::SUCCESS;
    }
}
