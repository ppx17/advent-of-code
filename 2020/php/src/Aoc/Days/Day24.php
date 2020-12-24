<?php


namespace Ppx17\Aoc2020\Aoc\Days;


class Day24 extends AbstractDay
{
    private array $tiles;
    private array $grid;
    private array $neighborCache = [];
    private array $directions = [
        'ne' => [-1, 1],
        'e' => [0, 2],
        'se' => [1, 1],
        'sw' => [1, -1],
        'w' => [0, -2],
        'nw' => [-1, -1]
    ];

    public function dayNumber(): int
    {
        return 24;
    }

    public function setUp(): void
    {
        $this->tiles = array_map(fn($x) => str_split($x), $this->getInputLines());
        $this->grid = $this->makeGrid();
    }

    public function part1(): string
    {
        return $this->countBlack($this->grid);
    }

    public function part2(): string
    {
        $grid = $this->grid;

        for ($day = 1; $day <= 100; $day++) {
            $grid = $this->iterate($grid);
        }

        return $this->countBlack($grid);
    }

    private function countBlack(array $grid): int
    {
        return collect($grid)
            ->flatten()
            ->sum(fn($x) => (int)$x);
    }

    private function iterate(array $grid): array
    {
        $newGrid = [];
        foreach ($grid as $y => $row) {
            foreach ($row as $x => $tile) {
                $newGrid[$y][$x] = $this->determineColor($grid, $y, $x);
                $neighbors = $this->determineNeighbors($y, $x);
                foreach ($neighbors as $n) {
                    if (isset($newGrid[$n[0]][$n[1]])) {
                        continue;
                    }
                    $newGrid[$n[0]][$n[1]] = $this->determineColor($grid, $n[0], $n[1]);
                }

            }
        }
        return $newGrid;
    }

    private function determineColor(array $grid, $y, $x): string
    {
        $neighbors = $this->determineNeighbors($y, $x);
        $blackCount = array_sum(array_map(fn($n) => ($grid[$n[0]][$n[1]] ?? false), $neighbors));

        return $grid[$y][$x] ? $blackCount === 1 || $blackCount === 2 : $blackCount === 2;
    }

    private function makeGrid(): array
    {
        $grid = [];
        foreach ($this->tiles as $directions) {
            $pos = [0, 0];
            $a = null;

            foreach ($directions as $l) {
                if ($l === 'n' || $l === 's') {
                    $a = $l;
                    continue;
                }
                if ($a !== null) {
                    $l = $a . $l;
                    $a = null;
                }

                $pos[0] += $this->directions[$l][0];
                $pos[1] += $this->directions[$l][1];
            }

            $grid[$pos[0]][$pos[1]] = !($grid[$pos[0]][$pos[1]] ?? false);
        }
        return $grid;
    }

    private function determineNeighbors(int $y, int $x): array
    {
        $this->neighborCache[$y * 5000 + $x] ??= array_map(fn($d) => [$y + $d[0], $x + $d[1]], $this->directions);
        return $this->neighborCache[$y * 5000 + $x];
    }
}
