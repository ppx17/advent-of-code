<?php

namespace Ppx17\Aoc2018\Days\Day15;


class Map
{
    public $allUnits;
    public $unitsByLocation;
    public $elves;
    public $goblins;
    private $walls;

    private $elfStrength;

    private $width;
    private $height;

    public function __construct(string $map, int $elfStrength = 3)
    {
        $this->elfStrength = $elfStrength;
        $this->loadMap($map);
    }

    public function isOccupied(Vector $vector): bool
    {
        return $vector->x < 1 || $vector->y < 1 ||
            $vector->y > $this->height ||
            $vector->x > $this->width ||
            ($this->walls[$vector->y][$vector->x] === true) ||
            $this->unitsByLocation[$vector->y][$vector->x] !== null;
    }

    public function print()
    {
        $grid = [];
        foreach ($this->walls as $y => $row) {
            foreach ($row as $x => $col) {
                $grid[$y][$x] = ($col === true) ? '#' : '.';
            }
        }
        foreach ($this->allUnits as $unit) {
            $grid[$unit->location->y][$unit->location->x] = $unit->type;
        }

        foreach ($grid as $row) {
            echo implode('', $row) . PHP_EOL;
        }
    }

    public function unitAt(Vector $location): ?Unit
    {
        return $this->unitsByLocation[$location->y][$location->x] ?? null;
    }

    public function neighborsFrom(Vector $location): array
    {
        $result = [];
        foreach ($location->neighbors() as $position) {
            $unit = $this->unitAt($position);
            if ($unit !== null) {
                $result[] = $unit;
            }
        }
        return $result;
    }

    public function unitDies(Unit $unit)
    {
        unset($this->unitsByLocation[$unit->location->y][$unit->location->x]);
        $this->removeFromArray($this->allUnits, $unit);

        if ($unit->type === 'E') {
            $this->removeFromArray($this->elves, $unit);
        } elseif ($unit->type === 'G') {
            $this->removeFromArray($this->goblins, $unit);
        }
    }

    public function moveUnit(Unit $unit, Vector $newLocation)
    {
        unset($this->unitsByLocation[$unit->location->y][$unit->location->x]);
        $this->unitsByLocation[$newLocation->y][$newLocation->x] = $unit;
        $unit->location = $newLocation;
    }

    private function removeFromArray(array &$array, Unit $unit)
    {
        array_splice($array, array_search($unit, $array), 1);
    }

    private function setMap(int $x, int $y, string $char)
    {
        $this->walls[$y][$x] = ($char === '#');
        if ($char === 'E' || $char === 'G') {
            $this->setUnit($x, $y, $char);
        }
    }

    private function setUnit(int $x, int $y, string $char): void
    {
        $Unit = new Unit(new Vector($x, $y), $char);

        $this->allUnits[] = $Unit;
        $this->unitsByLocation[$y][$x] = $Unit;

        if ($char === 'E') {
            $Unit->attackPower = $this->elfStrength;
            $this->elves[] = $Unit;
        } else {
            $this->goblins[] = $Unit;
        }
    }

    private function loadMap(string $map): void
    {
        $this->walls = [];
        $this->goblins = [];
        $this->elves = [];
        $this->allUnits = [];

        $rows = explode("\n", $map);

        // Remove top and bottom since they're always wall
        array_shift($rows);
        array_pop($rows);

        $this->height = count($rows);
        $this->width = strlen($rows[0]) - 2;

        foreach ($rows as $y => $row) {
            for ($x = 1; $x < strlen($row) - 1; $x++) {
                $this->setMap($x, $y + 1, $row[$x]);
            }
        }
    }
}