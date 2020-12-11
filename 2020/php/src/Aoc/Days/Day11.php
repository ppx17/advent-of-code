<?php


namespace Ppx17\Aoc2020\Aoc\Days;


class Day11 extends AbstractDay
{
    private array $map;
    private $width;
    private int $height;
    private int $runningPart = 1;
    private array $cleanMap;
    private array $directions;
    private int $maxNeighbors;

    public function dayNumber(): int
    {
        return 11;
    }

    public function setUp(): void
    {
        $this->cleanMap = array_map(fn($line) => str_split($line), $this->getInputLines());
        $this->height = count($this->cleanMap);
        $this->width = count($this->cleanMap[0]);
        $this->directions = [
            [-1, -1],
            [-1, 0],
            [-1, 1],
            [0, -1],
            [0, 1],
            [1, -1],
            [1, 0],
            [1, 1]
        ];
    }

    public function part1(): string
    {
        $this->maxNeighbors = 4;
        return $this->run(1);
    }

    public function part2(): string
    {
        $this->maxNeighbors = 5;
        return $this->run(2);
    }

    private function run(int $part)
    {
        $this->runningPart = $part;
        $this->map = $this->cleanMap;
        while(true) {
            $nextMap = $this->simulateStep();
            if($nextMap === $this->map) break;
            $this->map = $nextMap;
        }
        return substr_count($this->mapAsString(), '#');
    }

    private function mapAsString(): string
    {
        return implode("\n", array_map(fn($row) => implode('', $row), $this->map));
    }

    private function simulateStep(): array
    {
        $newMap = [];
        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                $newMap[$y][$x] = $this->transform($y, $x);
            }
        }
        return $newMap;
    }

    private function transform(int $y, int $x): string
    {
        $now = $this->map[$y][$x];
        if ($now === '.') return '.';

        $occupied = $this->occupied($y, $x);

        if ($now === 'L' && $occupied === 0) {
            return '#';
        }
        if ($now === '#' && $occupied >= $this->maxNeighbors) {
            return 'L';
        }
        return $now;
    }

    private function occupied(int $y, int $x): int
    {
        return $this->runningPart === 1
            ? $this->occupiedNeighborsNextDoor($y, $x)
            : $this->occupiedNeighborsInSight($y, $x);
    }

    private function occupiedNeighborsNextDoor(int $y, int $x): int
    {
        return array_sum(array_map(fn($dir) => (int)($this->map[$dir[0] + $y][$dir[1] + $x] === '#'), $this->directions));
    }

    private function occupiedNeighborsInSight(int $y, int $x): int
    {
        return array_sum(array_map(function($direction) use ($y, $x) {
            do {
                $y += $direction[0];
                $x += $direction[1];
            }while($this->map[$y][$x] === '.');

            return (int)($this->map[$y][$x] === '#');
        }, $this->directions));
    }
}
