<?php


namespace Ppx17\Aoc2015\Aoc\Days;


use Illuminate\Support\Collection;

class Day5 extends AbstractDay
{
    private Collection $strings;

    public function dayNumber(): int
    {
        return 5;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->strings = collect($this->getInputLines());
    }

    public function part1(): string
    {
        return $this
            ->strings
            ->filter(fn($s) => $this->isNice($s))
            ->count();
    }

    public function part2(): string
    {
        return $this
            ->strings
            ->filter(fn($s) => $this->isNiceNewModel($s))
            ->count();
    }

    private function isNice(string $string)
    {
        return $this->vowels($string)
            && $this->twice($string)
            && $this->badStrings($string);
    }

    private function vowels(string $string): bool
    {
        return collect(['a', 'e', 'i', 'o', 'u'])
                ->map(fn($vowel) => substr_count($string, $vowel))
                ->sum() >= 3;
    }

    private function twice(string $string)
    {
        for ($i = 0; $i < strlen($string) - 1; $i++) {
            if ($string[$i] === $string[$i + 1]) {
                return true;
            }
        }
        return false;
    }

    private function badStrings(string $string)
    {
        foreach (['ab', 'cd', 'pq', 'xy'] as $bad) {
            if (strpos($string, $bad) !== false) {
                return false;
            }
        }
        return true;
    }

    private function isNiceNewModel($string): bool
    {
        return $this->twoPair($string)
            && $this->replicateWithSeparator($string);
    }

    private function twoPair($string): bool
    {
        for ($i = 0; $i < strlen($string) - 3; $i++) {
            if (strpos($string, $string[$i] . $string[$i + 1], $i + 2) !== false) {
                return true;
            }
        }
        return false;
    }

    private function replicateWithSeparator($string): bool
    {
        for ($i = 0; $i < strlen($string) - 2; $i++) {
            if ($string[$i] === $string[$i + 2]) {
                return true;
            }
        }
        return false;
    }
}
