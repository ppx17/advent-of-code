<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Illuminate\Support\Collection;

class Day4 extends AbstractDay
{
    private string $low;
    private string $high;
    private Collection $numbers;
    private Collection $part1;
    private array $patterns;
    private array $antiPatterns;

    public function dayNumber(): int
    {
        return 4;
    }

    public function setUp(): void
    {
        parent::setUp();
        $parts = explode("-", trim($this->getInput()));
        $this->low = $parts[0];
        $this->high = $parts[1];

        $this->numbers = collect($this->numbers());

        // Cache patterns / anti patterns, saves about 600µs
        $this->patterns = [];
        $this->antiPatterns = [];
        for ($i = 0; $i <= 9; $i++) {
            $this->patterns[] = str_repeat($i, 2);
            $this->antiPatterns[] = str_repeat($i, 3);
        }
    }

    public function part1(): string
    {
        return $this
            ->numbers
            ->filter(function ($number) {
                foreach ($this->patterns as $pattern) {
                    if (strpos($number, $pattern) !== false) {
                        return true;
                    }
                }
                return false;
            })
            ->tap(fn($collection) => $this->part1 = $collection)
            ->count();
    }

    public function part2(): string
    {
        return $this
            ->part1
            ->filter(function ($number) {
                foreach ($this->patterns as $i => $pattern) {
                    if (strpos($number, $pattern) !== false &&
                        strpos($number, $this->antiPatterns[$i]) === false) {
                        return true;
                    }
                }
                return false;
            })
            ->count();
    }

    private function numbers(): \Generator
    {
        $active = $this->low;

        while (true) {
            for ($i = 1; $i < 6; $i++) {
                if ($active[$i] < $active[$i - 1]) {
                    $active[$i] = $active[$i - 1];
                }
            }
            if ($active > $this->high) {
                break;
            }
            yield $active;
            $active = (string)(intval($active) + 1);
        }
    }
}
