<?php

namespace App\Service;

class FizzBuzzService
{
    private const FIZZ_DIVISOR = 3;
    private const BUZZ_DIVISOR = 5;
    private const FIZZ_BUZZ_DIVISOR = self::FIZZ_DIVISOR * self::BUZZ_DIVISOR;

    public function generate(int $limit): \Generator
    {
        for ($i = 1; $i <= $limit; $i++) {
            yield $this->getValue($i);
        }
    }

    public function generateArray(int $limit): array
    {
        return iterator_to_array($this->generate($limit), false);
    }

    public function getValue(int $number): string
    {
        return match (true) {
            $number % self::FIZZ_BUZZ_DIVISOR === 0 => 'FizzBuzz',
            $number % self::FIZZ_DIVISOR === 0 => 'Fizz',
            $number % self::BUZZ_DIVISOR === 0 => 'Buzz',
            default => (string) $number,
        };
    }

    public function isValidLimit(mixed $limit): bool
    {
        return is_numeric($limit) && (int) $limit > 0 && (int) $limit == $limit;
    }
}