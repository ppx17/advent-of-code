<?php

namespace Ppx17\Aoc2020\Aoc\Days;

use Illuminate\Support\Collection;

class Day5 extends AbstractDay
{
    private Collection $seats;

    public function dayNumber(): int
    {
        return 5;
    }

    public function setUp(): void
    {
        $this->seats = collect($this->getInputLines())
            ->map(fn($s) => bindec(str_replace(['F', 'L', 'B', 'R'], [0, 0, 1, 1], $s)));
    }

    public function part1(): string
    {
        return $this->seats->max();
    }

    public function part2(): string
    {
        return collect(range($this->seats->min(), $this->seats->max()))
            ->first(fn($id) => !$this->seats->contains($id)
                && $this->seats->contains($id + 1)
                && $this->seats->contains($id - 1));
    }
}
