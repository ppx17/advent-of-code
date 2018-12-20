<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 13:28
 */

namespace Ppx17\Aoc2018\Days\Day17;


use Ppx17\Aoc2018\Days\Common\Vector;

class Map
{
    public $minX = PHP_INT_MAX;
    public $maxX = PHP_INT_MIN;
    public $minY = PHP_INT_MAX;
    public $maxY = PHP_INT_MIN;
    private $grid;

    public function __construct()
    {
        $this->grid = [];
    }

    public function addVein(Vector $start, Vector $end): void
    {
        $this->setBetween($start, $end, '#');
        $this->maxX = max($this->maxX, $end->x);
        $this->minX = min($this->minX, $start->x);

        $this->minY = min($this->minY, $start->y);
        $this->maxY = max($this->maxY, $end->y);
    }

    public function setBetween(Vector $start, Vector $end, string $symbol): void
    {
        for ($y = $start->y; $y <= $end->y; $y++) {
            for ($x = $start->x; $x <= $end->x; $x++) {
                $this->grid[$y][$x] = $symbol;
            }
        }
    }

    public function print(): void
    {
        for ($y = 0; $y <= $this->maxY; $y++) {
            for ($x = $this->minX - 1; $x <= $this->maxX + 1; $x++) {
                if ($y === 0) {
                    if ($x === 500) {
                        echo '+';
                    } else {
                        echo ' ';
                    }
                } else {
                    echo $this->grid[$y][$x] ?? '.';
                }
            }
            echo PHP_EOL;
        }
    }

    public function isOutside(Vector $location): bool
    {
        return $location->y > $this->maxY;
    }

    public function isFree(Vector $location): bool
    {
        $symbol = $this->grid[$location->y][$location->x] ?? null;
        return $symbol === null || $symbol === '|';
    }

    public function set(Vector $location, string $symbol): void
    {
        $this->grid[$location->y][$location->x] = $symbol;
    }

    public function setRunningWater(Vector $location): void
    {
        $this->set($location, '|');
    }

    public function setStillWater(Vector $location): void
    {
        $this->set($location, '~');
    }

    public function countWater(): int
    {
        $water = 0;
        for ($y = $this->minY; $y <= $this->maxY; $y++) {
            for ($x = $this->minX -1; $x <= $this->maxX + 1; $x++) {
                if (isset($this->grid[$y][$x]) && ($this->grid[$y][$x] === '~' || $this->grid[$y][$x] === '|')) {
                    $water++;
                }
            }
        }
        return $water;
    }

    public function countStillWater(): int
    {
        $water = 0;
        for ($y = $this->minY; $y <= $this->maxY; $y++) {
            for ($x = $this->minX -1; $x <= $this->maxX + 1; $x++) {
                if (isset($this->grid[$y][$x]) && ($this->grid[$y][$x] === '~')) {
                    $water++;
                }
            }
        }
        return $water;
    }
}