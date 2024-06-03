<?php

declare(strict_types=1);

namespace App\Command;

use Faker\Factory;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\CSV\Writer as CSVWriter;
use Random\Randomizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'data:participants:generate',
    description: 'Add a short description for your command',
)]
class DataParticipantsGenerateCommand extends Command
{
    public function __construct(
        #[Autowire('%storage.dir%')]
        private readonly string $storageDir,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filename', InputArgument::REQUIRED, 'Output filename (with extension). Example: participants.csv.')
            ->addArgument('count', InputArgument::OPTIONAL, 'Rows count', 50)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename');
        $outputFile = $this->storageDir . '/' . $filename;
        $count = (int)$input->getArgument('count');

        $writer = new CSVWriter();
        $writer->openToFile($outputFile);

        $writer->addRow(Row::fromValues(['ФИО', 'E-Mail', 'Учреждение образования']));

        $faker = Factory::create('ru_RU');
        foreach (range(1, $count) as $_) {
            $row = Row::fromValues([
                $faker->name(),
                $faker->email(),
                $this->randomEducation(),
            ]);

            $writer->addRow($row);
        }

        $writer->close();

        return Command::SUCCESS;
    }

    private function randomEducation(): string
    {
        $establishments = [
            'Гродненский государственный университет имени Янки Купалы',
            'Гродненский государственный аграрный университет',
            'Гродненский государственный медицинский университет',
            'Белорусский государственный университет',
            'Белорусский национальный технический университет',
            'Белорусский государственный университет информатики и радиоэлектроники',
        ];

        $randomizer = new Randomizer();

        return $randomizer->shuffleArray($establishments)[0];
    }
}
