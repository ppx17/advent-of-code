<?php

namespace Ppx17\Aoc2019\Aoc\Days;

use Illuminate\Support\Collection;

class Day6 extends AbstractDay
{
    private const SUN = 'COM';
    private const SANTA = 'SAN';
    private const YOU = 'YOU';
    private Collection $orbits;
    private Collection $parents;

    public function dayNumber(): int
    {
        return 6;
    }

    public function setUp(): void
    {
        $this->parents = new Collection();
        $this->orbits = collect($this->getInputLines())
            ->map(fn($o) => explode(")", $o))
            ->each(fn($o) => $this->parents->put($o[1], $o[0]))
            ->groupBy(fn($o) => $o[0]);
    }

    public function part1(): string
    {
        return (string)$this->countTree(self::SUN, 1);
    }

    public function part2(): string
    {
        $youRoot = $sanRoot = [];
        $currEl = self::YOU;
        while ($currEl !== self::SUN) {
            $currEl = $this->parents->get($currEl);
            $youRoot[] = $currEl;
        }

        $currEl = self::SANTA;
        while ($currEl !== self::SUN) {
            $currEl = $this->parents->get($currEl);
            $sanRoot[] = $currEl;
            if (in_array($currEl, $youRoot)) {
                return (string)(array_search($currEl, $youRoot) + count($sanRoot) - 1);
            }
        };
        return 'unknown';
    }

    private function countTree(string $element, int $level): int
    {
        if (!$this->orbits->has($element)) {
            return 0;
        }
        return ($this->orbits->get($element)->count() * $level) +
            $this
                ->orbits
                ->get($element)
                ->map(fn($childElement) => $this->countTree($childElement[1], $level + 1))
                ->sum();
    }
}
