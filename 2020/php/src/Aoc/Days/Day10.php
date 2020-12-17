<?php

namespace Ppx17\Aoc2020\Aoc\Days;

use Illuminate\Support\Collection;

class Day10 extends AbstractDay
{
    private Collection $adapters;
    private Collection $adaptersByKey;

    public function dayNumber(): int
    {
        return 10;
    }

    public function setUp(): void
    {
        $this->adapters = collect($this->getInputLines())->map(fn($x) => (int)$x)->sort()->values();
        $this->adaptersByKey = $this->adapters->flip();
    }

    public function part1(): string
    {
        $differences = [];
        $last = 0;
        $this->adapters->each(function ($adapter) use (&$last, &$differences) {
            $diff = $adapter - $last;
            $last = $adapter;
            $differences[$diff] ??= 0;
            $differences[$diff]++;
        });
        $differences[3]++; // account for the device adapter

        return $differences[1] * $differences[3];
    }

    public function part2(): string
    {
        $routes = [$this->adapters->count() - 1 => 1]; // last adapter can only reach device
        for ($index = $this->adapters->count() - 2; $index >= 0; $index--) {
            $routes[$index] = Collection::times(3)
                ->map(fn($x) => $this->adapters[$index] + $x)
                ->map(fn($nextAdapter) => $routes[$this->adaptersByKey->get($nextAdapter)] ?? 0)
                ->sum();
        }

        return Collection::times(3)
            ->filter(fn($x) => isset($this->adaptersByKey[$x]))
            ->map(fn($x) => $routes[$x - 1])
            ->sum();
    }
}
