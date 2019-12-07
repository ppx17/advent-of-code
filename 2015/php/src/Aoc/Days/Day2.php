<?php


namespace Ppx17\Aoc2015\Aoc\Days;


use Illuminate\Support\Collection;

class Day2 extends AbstractDay
{
    private Collection $boxes;

    public function dayNumber(): int
    {
        return 2;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->boxes = collect($this->getInputLines())
            ->map(fn($box) => explode('x', $box));
    }

    public function part1(): string
    {
        return $this
            ->boxes
            ->map(fn($box) => collect([2 * $box[0] * $box[1], 2 * $box[1] * $box[2], 2 * $box[2] * $box[0]]))
            ->map(fn(Collection $box) => $box->sum() + $box->min() / 2)
            ->sum();
    }

    public function part2(): string
    {
        return $this
            ->boxes
            ->map(function ($box) {
                sort($box);
                return $box[0] * 2 + $box[1] * 2 + $box[0] * $box[1] * $box[2];
            })
            ->sum();
    }
}
