<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Illuminate\Support\Collection;

class Day5 extends AbstractDay
{
    private Collection $seatIds;

    public function dayNumber(): int
    {
        return 5;
    }

    public function setUp(): void
    {
        $this->seatIds = collect($this->getInputLines())
            ->map(fn($p) => $this->seatId($p));
    }

    private function seatId(string $seat)
    {
        return bindec(str_replace(['F', 'L', 'B', 'R'], [0, 0, 1, 1], $seat));
    }

    public function part1(): string
    {
        return $this->seatIds->max();
    }

    public function part2(): string
    {
        return collect(range($this->seatIds->min(), $this->seatIds->max()))
            ->first(fn($id) => !$this->seatIds->contains($id)
                && $this->seatIds->contains($id + 1)
                && $this->seatIds->contains($id - 1));
    }
}
