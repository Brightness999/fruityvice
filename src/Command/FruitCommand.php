<?php

namespace App\Command;

use App\Repository\FruitRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fruit',
    description: 'Add a short description for your command',
)]
class FruitCommand extends Command
{
    private $fruitReposity;

    protected static $defaultName = 'app:fruit';

    protected static $defaultDescription = 'Creates a new user.';

    public function __construct(FruitRepository $fruitRepository)
    {
        $this->fruitReposity = $fruitRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('New fruits will be added...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $this->fruitReposity->add();

        $io->success('New fruits added successfully.');

        return Command::SUCCESS;
    }
}
