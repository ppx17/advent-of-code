<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Illuminate\Support\Collection;

class Day6 extends AbstractDay
{
    private Collection $groups;

    public function dayNumber(): int
    {
        return 6;
    }

    public function setUp(): void
    {
        $this->groups = collect(explode("\n\n", $this->getInput()));
    }

    public function part1(): string
    {
        return $this->groups
            ->map(fn($group) => str_replace("\n", "", $group))
            ->map(fn($group) => str_split($group))
            ->map(fn($group) => array_unique($group))
            ->map(fn($group) => count($group))
            ->sum();
    }

    public function part2(): string
    {
        return $this->groups
            ->map(fn($group) => collect(explode("\n", $group))->map(fn($x) => str_split($x)))
            ->map(fn(Collection $group) => $group->count() === 1 ? $group->first() : array_intersect(...$group->toArray()))
            ->map(fn($group) => count($group))
            ->sum();
    }
}
