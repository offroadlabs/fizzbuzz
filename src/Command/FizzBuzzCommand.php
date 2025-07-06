<?php

namespace App\Command;

use App\Service\FizzBuzzService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'fizzbuzz',
    description: 'Generate FizzBuzz sequence from 1 to N',
    aliases: ['fb']
)]
class FizzBuzzCommand extends Command
{
    public function __construct(
        private readonly FizzBuzzService $fizzBuzzService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('limit', InputArgument::REQUIRED, 'Upper limit for FizzBuzz sequence (N)')
            ->setHelp('This command generates the FizzBuzz sequence from 1 to the specified limit N.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $limit = $input->getArgument('limit');

        if (!$this->fizzBuzzService->isValidLimit($limit)) {
            $io->error('Invalid limit. Please provide a positive integer.');
            return Command::FAILURE;
        }

        $limit = (int) $limit;
        
        $io->title("FizzBuzz sequence from 1 to {$limit}");
        
        $results = $this->fizzBuzzService->generate($limit);
        
        foreach ($results as $result) {
            $io->writeln($result);
        }

        $io->success("Generated FizzBuzz sequence for {$limit} numbers.");
        
        return Command::SUCCESS;
    }

}