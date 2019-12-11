<?php


namespace Ppx17\Aoc2015\Aoc\Days;


use Illuminate\Support\Collection;

class Day8 extends AbstractDay
{
    private Collection $content;
    private int $characters;

    public function dayNumber(): int
    {
        return 8;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->content = collect($this->getInputLines());
        $this->characters = $this
            ->content
            ->map(fn($x) => strlen($x))
            ->sum();
    }

    public function part1(): string
    {
        $memory = $this
            ->content
            ->map(fn($x) => substr($x, 1, -1)) // Remove containing quotes
            ->map(fn($x) => str_replace('\\\\', '\\', $x))
            ->map(fn($x) => str_replace('\\"', '"', $x))
            ->map(fn($x) => preg_replace('#\\\\x([0-9a-f]{2})#', "*", $x))
            ->map(fn($x) => strlen($x))
            ->sum();

        return (string)($this->characters - $memory);
    }

    public function part2(): string
    {
        $doubleEncoded = $this
            ->content
            ->map(fn($x) => addslashes($x))
            ->map(fn($x) => strlen($x) + 2)
            ->sum();

        return (string)$doubleEncoded - $this->characters;
    }
}
