<?php

namespace App\Tests\Service;

use App\Service\FizzBuzzService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FizzBuzzServiceTest extends TestCase
{
    private FizzBuzzService $fizzBuzzService;

    protected function setUp(): void
    {
        $this->fizzBuzzService = new FizzBuzzService();
    }

    #[DataProvider('getValueProvider')]
    public function testGetValue(int $input, string $expected): void
    {
        $result = $this->fizzBuzzService->getValue($input);
        
        $this->assertSame($expected, $result);
    }

    public static function getValueProvider(): array
    {
        return [
            // Regular numbers
            [1, '1'],
            [2, '2'],
            [4, '4'],
            [7, '7'],
            [8, '8'],
            [11, '11'],
            [13, '13'],
            [14, '14'],
            [16, '16'],
            [17, '17'],
            [19, '19'],
            [22, '22'],
            
            // Fizz (divisible by 3)
            [3, 'Fizz'],
            [6, 'Fizz'],
            [9, 'Fizz'],
            [12, 'Fizz'],
            [18, 'Fizz'],
            [21, 'Fizz'],
            [24, 'Fizz'],
            [27, 'Fizz'],
            
            // Buzz (divisible by 5)
            [5, 'Buzz'],
            [10, 'Buzz'],
            [20, 'Buzz'],
            [25, 'Buzz'],
            [35, 'Buzz'],
            [40, 'Buzz'],
            [50, 'Buzz'],
            
            // FizzBuzz (divisible by both 3 and 5)
            [15, 'FizzBuzz'],
            [30, 'FizzBuzz'],
            [45, 'FizzBuzz'],
            [60, 'FizzBuzz'],
            [75, 'FizzBuzz'],
            [90, 'FizzBuzz'],
            [105, 'FizzBuzz'],
            [150, 'FizzBuzz'],
        ];
    }

    #[DataProvider('generateArrayProvider')]
    public function testGenerateArray(int $limit, array $expected): void
    {
        $result = $this->fizzBuzzService->generateArray($limit);
        
        $this->assertSame($expected, $result);
    }

    public static function generateArrayProvider(): array
    {
        return [
            [1, ['1']],
            [3, ['1', '2', 'Fizz']],
            [5, ['1', '2', 'Fizz', '4', 'Buzz']],
            [15, [
                '1', '2', 'Fizz', '4', 'Buzz', 'Fizz', '7', '8', 'Fizz', 'Buzz',
                '11', 'Fizz', '13', '14', 'FizzBuzz'
            ]],
        ];
    }

    public function testGenerate(): void
    {
        $result = $this->fizzBuzzService->generate(5);
        
        $this->assertInstanceOf(\Generator::class, $result);
        
        $array = iterator_to_array($result, false);
        $expected = ['1', '2', 'Fizz', '4', 'Buzz'];
        
        $this->assertSame($expected, $array);
    }

    public function testGenerateEmpty(): void
    {
        $result = $this->fizzBuzzService->generate(0);
        
        $this->assertInstanceOf(\Generator::class, $result);
        
        $array = iterator_to_array($result, false);
        
        $this->assertEmpty($array);
    }

    #[DataProvider('isValidLimitProvider')]
    public function testIsValidLimit(mixed $input, bool $expected): void
    {
        $result = $this->fizzBuzzService->isValidLimit($input);
        
        $this->assertSame($expected, $result);
    }

    public static function isValidLimitProvider(): array
    {
        return [
            // Valid limits
            [1, true],
            [5, true],
            [100, true],
            ['1', true],
            ['5', true],
            ['100', true],
            [1.0, true],
            [5.0, true],
            
            // Invalid limits
            [0, false],
            [-1, false],
            [-5, false],
            ['0', false],
            ['-1', false],
            ['abc', false],
            ['', false],
            [1.5, false],
            [3.14, false],
            ['1.5', false],
            [null, false],
            [true, false],
            [false, false],
            [[], false],
            [new \stdClass(), false],
        ];
    }

    public function testLargeNumber(): void
    {
        $result = $this->fizzBuzzService->getValue(1000000);
        
        $this->assertSame('Buzz', $result);
    }

    public function testVeryLargeNumber(): void
    {
        $result = $this->fizzBuzzService->getValue(999999999);
        
        $this->assertSame('Fizz', $result);
    }

    public function testMemoryEfficiencyWithGenerator(): void
    {
        $memoryBefore = memory_get_usage();
        
        $generator = $this->fizzBuzzService->generate(10000);
        
        $count = 0;
        foreach ($generator as $value) {
            $count++;
            if ($count === 100) {
                break;
            }
        }
        
        $memoryAfter = memory_get_usage();
        $memoryUsed = $memoryAfter - $memoryBefore;
        
        $this->assertLessThan(1024 * 1024, $memoryUsed, 'Generator should use minimal memory');
        $this->assertSame(100, $count);
    }
}