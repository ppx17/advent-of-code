<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day11;

use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;

class Map
{
    public Collection $map;
    private string $defaultColor;

    public function __construct($defaultColor = '.')
    {
        $this->defaultColor = $defaultColor;
        $this->map = new Collection();
    }

    public function color(Vector $location)
    {
        return $this->map->get($location->y, new Collection())->get($location->x, $this->defaultColor);
    }

    public function paint(Vector $location, string $color)
    {
        if (!$this->map->has($location->y)) {
            $this->map->put($location->y, new Collection());
        }
        $this->map[$location->y]->put($location->x, $color);
    }

    public function __toString()
    {
        $result = '';
        for($y = $this->minY(); $y <= $this->maxY(); $y++) {
            for($x = $this->minX(); $x <= $this->maxX(); $x++) {
                $result .= $this->map[$y][$x] ?? $this->defaultColor;
            }
            $result .= "\n";
        }
        return $result;
    }

    public function countTiles(?string $filter = null): int
    {
        return $this->map
            ->map(fn(Collection $row) => $row->filter(fn($x) => ($filter) === null ? true : $x === $filter)->count())
            ->sum();
    }

    private function minY(): int
    {
        return (int)$this->map->keys()->min();
    }

    private function maxY(): int
    {
        return (int)$this->map->keys()->max();
    }

    private function minX(): int
    {
        return (int)$this->map
            ->map(fn($row) => $row->keys()->min())
            ->min();
    }

    private function maxX(): int
    {
        return (int)$this->map
            ->map(fn($row) => $row->keys()->max())
            ->max();
    }
}
