<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\User\UserGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'user:generate',
    description: 'Add a short description for your command',
)]
final class UserGenerateCommand extends Command
{
    public function __construct(private readonly UserGenerator $userGenerator)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'count',
                null,
                InputOption::VALUE_OPTIONAL,
                'Count of users. If not defined only one user will be generated.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $count = $input->getOption('count');

        if ($count !== null) {
            $count = (int)$count;
        }

        if ($count === null || $count === 1) {
            $user = $this->userGenerator->generateOne(true);
            $io->success('User was successfully generated! E-Mail: ' . $user->getEmail());
            return Command::SUCCESS;
        }

        $this->userGenerator->generateMass($count);
        $io->success("{$count} users was successfully generated.");

        return Command::SUCCESS;
    }
}
