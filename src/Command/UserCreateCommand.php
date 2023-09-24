<?php

namespace App\Command;

use App\Dto\FullName;
use App\Entity\User;
use App\Service\Util\PasswordGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Nette\Utils\Validators;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'user:create',
    description: 'Add a short description for your command',
)]
class UserCreateCommand extends Command
{
    private readonly Generator $faker;

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly PasswordGeneratorService $passwordGenerator,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
        $this->faker = Factory::create('ru_RU');
    }

    protected function configure(): void
    {
        $this
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Create administrator');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $helper = $this->getHelper('question');
        $fullNameQuestion = $this->getFullNameQuestion();
        $emailQuestion = $this->getEmailQuestion();

        $fullName = $helper->ask($input, $output, $fullNameQuestion);
        $email = $helper->ask($input, $output, $emailQuestion);

        $fullNameDto = FullName::fromString($fullName);

        $user = new User();
        $user->setEmail($email)
            ->setFirstName($fullNameDto->firstName)
            ->setLastName($fullNameDto->lastName)
            ->setMiddleName($fullNameDto->middleName);

        $password = $this->passwordGenerator->getRandomPassword();
        $hashedPassword = $this->hasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success("Пользователь успешно создан. Данные для входа:
            E-Mail: {$user->getEmail()}
            Пароль: {$password}
            Запишите, либо запомните пароль!");

        return Command::SUCCESS;
    }

    private function getFullNameQuestion(): Question
    {
        $fullNameQuestion = new Question('Введите ФИО: ');
        $fullNameQuestion->setValidator(static function (?string $answer) {
            $pattern = '/^([А-ЯЁA-Z][а-яёa-z]+(?:-+[А-ЯЁA-Z][а-яёa-z]+)?\s){1,2}[А-ЯЁA-Z][а-яёa-z]+(?:-+[А-ЯЁA-Z][а-яёa-z]+)?(?:\s[А-ЯЁA-Z][а-яёa-z]+(?:-+[А-ЯЁA-Z][а-яёa-z]+)?)?$/u';
            if (!preg_match($pattern, $answer)) {
                throw new \RuntimeException(
                    'ФИО должно содержать 3 слова через два пробела, либо 2 слова через пробел'
                );
            }

            return $answer;
        });

        return $fullNameQuestion;
    }

    private function getEmailQuestion(): Question
    {
        $question = new Question('E-Mail: ');
        $question->setValidator(static function (?string $value) {
            if ($value === null || !Validators::isEmail($value)) {
                throw new \InvalidArgumentException('Введите корректный E-Mail!');
            }

            return $value;
        });

        return $question;
    }
}
