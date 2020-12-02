<?php


namespace Ppx17\Aoc2020\Aoc\Days;


class Day1 extends AbstractDay
{
    private array $amounts;

    public function setUp(): void
    {
        $this->amounts = array_map(fn($x) => (int)$x, $this->getInputLines());
    }

    public function dayNumber(): int
    {
        return 1;
    }

    public function part1(): string
    {
        foreach ($this->amounts as $a) {
            foreach ($this->amounts as $b) {
                if ($a + $b === 2020) {
                    return $a * $b;
                }
            }
        }
        return '';
    }

    public function part2(): string
    {
        foreach ($this->amounts as $a) {
            foreach ($this->amounts as $b) {
                foreach ($this->amounts as $c) {
                    if ($a + $b + $c === 2020) {
                        return $a * $b * $c;
                    }
                }
            }
        }
        return '';
    }
}
