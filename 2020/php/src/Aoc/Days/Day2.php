<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Illuminate\Support\Collection;

class Day2 extends AbstractDay
{
    private Collection $matches;

    public function setUp(): void
    {
        preg_match_all(
            "#([0-9]+)-([0-9]+) ([a-z]): ([a-z]+)#",
            $this->getInput(),
            $matches,
            PREG_SET_ORDER
        );

        $this->matches = collect($matches);
    }

    public function dayNumber(): int
    {
        return 2;
    }

    public function part1(): string
    {
        return $this->countWith(function($match, $min, $max, $letter, $password) {
            $occurrences = substr_count($password, $letter);
            return ($occurrences >= $min && $occurrences <= $max);
        });
    }

    public function part2(): string
    {
        return $this->countWith(function($match, $min, $max, $letter, $password) {
            return ($password[$min - 1] === $letter xor $password[$max - 1] === $letter);
        });
    }

    private function countWith(callable $callable) {
        return $this
            ->matches
            ->filter(fn($match) => $callable(...$match))
            ->count();
    }
}