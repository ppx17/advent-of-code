<?php


namespace Ppx17\Aoc2020\Aoc\Days;


class Day25 extends AbstractDay
{
    private const MOD = 20_201_227;
    private int $doorPublic;
    private int $cardPublic;

    public function dayNumber(): int
    {
        return 25;
    }

    public function setUp(): void
    {
        [$this->doorPublic, $this->cardPublic] = array_map(fn($x) => (int)$x, $this->getInputLines());
    }

    public function part1(): string
    {
        $loops = $key = 1;
        while($loops !== $this->cardPublic) {
            $loops = ($loops * 7) % self::MOD;
            $key = ($key * $this->doorPublic) % self::MOD;
        }
        return $key;
    }

    public function part2(): string
    {
        return '';
    }
}