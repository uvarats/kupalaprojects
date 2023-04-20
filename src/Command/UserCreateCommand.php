<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'user:create',
    description: 'Add a short description for your command',
)]
class UserCreateCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Create administrator')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $fullNameQuestion = $this->getFullNameQuestion();
        $fullName = $helper->ask($input, $output, $fullNameQuestion);
        dd($fullName);

        return Command::SUCCESS;
    }

    private function getFullNameQuestion(): Question
    {
        $fullNameQuestion = new Question('Введите ФИО: ');
        $fullNameQuestion->setValidator(static function (string $answer) {
            $pattern = '/^([А-ЯЁ][а-яё]+(?:-+[А-ЯЁ][а-яё]+)?\s){1,2}[А-ЯЁ][а-яё]+(?:-+[А-ЯЁ][а-яё]+)?(?:\s[А-ЯЁ][а-яё]+(?:-+[А-ЯЁ][а-яё]+)?)?$/';
            if (!preg_match($pattern, $answer)) {
                throw new \RuntimeException(
                    'ФИО должно содержать 3 слова через два пробела, либо 2 слова через пробел'
                );
            }

            return $answer;
        });

        return $fullNameQuestion;
    }
}
