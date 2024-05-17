<?php

declare(strict_types=1);

namespace App\Command;

use App\Feature\Account\Repository\UserRepository;
use App\Feature\Core\Service\UserRoleService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'user:admin:grant',
    description: 'Add a short description for your command',
)]
class UserAdminGrantCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserRoleService $roleService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'E-Mail пользователя, которому необходимо предоставить права администратора')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if ($user === null) {
            $io->error('Пользователь с e-mail ' . $email . ' не обнаружен.');

            return Command::FAILURE;
        }

        if ($user->hasRole('ROLE_ADMIN')) {
            $io->warning('Данный пользователь уже является администратором.');

            return Command::SUCCESS;
        }

        $this->roleService->grantRole($user, 'ROLE_ADMIN');

        $io->success('Пользователю с e-mail ' . $email . ' успешно назначена роль администратора.');

        return Command::SUCCESS;
    }
}
