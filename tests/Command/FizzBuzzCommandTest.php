<?php

namespace App\Tests\Command;

use App\Command\FizzBuzzCommand;
use App\Service\FizzBuzzService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class FizzBuzzCommandTest extends TestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $fizzBuzzService = new FizzBuzzService();
        $command = new FizzBuzzCommand($fizzBuzzService);
        
        $application = new Application();
        $application->add($command);
        
        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteWithValidInput(): void
    {
        $this->commandTester->execute(['limit' => '5']);

        $this->assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());
        
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('FizzBuzz sequence from 1 to 5', $output);
        $this->assertStringContainsString('1', $output);
        $this->assertStringContainsString('2', $output);
        $this->assertStringContainsString('Fizz', $output);
        $this->assertStringContainsString('4', $output);
        $this->assertStringContainsString('Buzz', $output);
        $this->assertStringContainsString('Generated FizzBuzz sequence for 5 numbers', $output);
    }

    public function testExecuteWithFizzBuzzSequence(): void
    {
        $this->commandTester->execute(['limit' => '15']);

        $this->assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());
        
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('FizzBuzz sequence from 1 to 15', $output);
        $this->assertStringContainsString('FizzBuzz', $output);
        $this->assertStringContainsString('Generated FizzBuzz sequence for 15 numbers', $output);
    }

    #[DataProvider('invalidInputProvider')]
    public function testExecuteWithInvalidInput(string $input): void
    {
        $this->commandTester->execute(['limit' => $input]);

        $this->assertSame(Command::FAILURE, $this->commandTester->getStatusCode());
        
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Invalid limit. Please provide a positive integer.', $output);
    }

    public static function invalidInputProvider(): array
    {
        return [
            ['0'],
            ['-1'],
            ['abc'],
            ['1.5'],
            [''],
        ];
    }

    public function testExecuteWithStringNumber(): void
    {
        $this->commandTester->execute(['limit' => '10']);

        $this->assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());
        
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('FizzBuzz sequence from 1 to 10', $output);
        $this->assertStringContainsString('Generated FizzBuzz sequence for 10 numbers', $output);
    }

    public function testCommandConfiguration(): void
    {
        $fizzBuzzService = new FizzBuzzService();
        $command = new FizzBuzzCommand($fizzBuzzService);

        $this->assertSame('fizzbuzz', $command->getName());
        $this->assertSame('Generate FizzBuzz sequence from 1 to N', $command->getDescription());
        $this->assertContains('fb', $command->getAliases());
        
        $definition = $command->getDefinition();
        $this->assertTrue($definition->hasArgument('limit'));
        $this->assertTrue($definition->getArgument('limit')->isRequired());
        $this->assertSame('Upper limit for FizzBuzz sequence (N)', $definition->getArgument('limit')->getDescription());
    }

    public function testExecuteWithLargeNumber(): void
    {
        $this->commandTester->execute(['limit' => '100']);

        $this->assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());
        
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('FizzBuzz sequence from 1 to 100', $output);
        $this->assertStringContainsString('Generated FizzBuzz sequence for 100 numbers', $output);
        
        // Check that output contains expected patterns
        $this->assertStringContainsString('Fizz', $output);
        $this->assertStringContainsString('Buzz', $output);
        $this->assertStringContainsString('FizzBuzz', $output);
    }
}