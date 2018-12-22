<?php

namespace Ppx17\Aoc2018\Days\Day22;


use Ppx17\Aoc2018\Days\Common\Vector;

class Map
{
    private $bottomRight;
    private $limit;

    private $target;
    private $depth;

    private $type = [];
    private $geologicIndex = [];
    private $erosionLevel = [];


    public function __construct(Vector $target, int $depth)
    {
        $this->bottomRight = $target;
        $this->limit = new Vector($target->x * 8, $target->y * 8);
        $this->target = $target;
        $this->depth = $depth;
        $this->resolveTypes();
    }

    public function getTypeByVector(Vector $position): ?int
    {
        return $this->getTypeDynamic($position->x, $position->y);
    }

    public function getType($x, $y): ?int
    {
        $type = $this->type[$y][$x];
        return $type;
//        if($type === null) {
//            throw new \Exception(sprintf("Type of %s,%s asked in map of size %s,%s",
//                $x, $y, $this->bottomRight->x, $this->bottomRight->y));
//        }
//        return $type;
    }

    public function getTypeDynamic($x, $y): ?int
    {
        if ($x > $this->bottomRight->x) {
            $this->growX($x);
        }
        if ($y > $this->bottomRight->y) {
            $this->growY($y);
        }
        return $this->getType($x, $y);
    }

    public function riskLevel(): int
    {
        return array_sum(array_map(function ($row) {
            return array_sum($row);
        }, $this->type));
    }

    public function setLimit(Vector $limit): void
    {
        $this->limit = $limit;
    }

    public function getLimit(): Vector
    {
        return $this->limit;
    }

    private function resolveTypes()
    {
        for ($y = 0; $y <= $this->target->y; $y++) {
            for ($x = 0; $x <= $this->target->x; $x++) {
                $this->resolveCoordinate($x, $y);
            }
        }
    }

    private function growX(int $newX)
    {
        $newX = min($newX, $this->limit->x);
        for ($y = 0; $y <= $this->bottomRight->y; $y++) {
            for ($x = $this->bottomRight->x + 1; $x <= $newX; $x++) {
                $this->resolveCoordinate($x, $y);
            }
        }
        $this->bottomRight->x = $newX;
    }

    private function growY(int $newY)
    {
        $newY = min($newY, $this->limit->y);
        for ($y = $this->bottomRight->y + 1; $y <= $newY; $y++) {
            for ($x = 0; $x <= $this->bottomRight->x; $x++) {
                $this->resolveCoordinate($x, $y);
            }
        }
        $this->bottomRight->y = $newY;
    }

    private function resolveCoordinate(int $x, int $y): void
    {
        $this->geologicIndex[$y][$x] = $this->resolveGeologicIndex($x, $y);
        $this->erosionLevel[$y][$x] = ($this->geologicIndex[$y][$x] + $this->depth) % 20183;
        $this->type[$y][$x] = $this->erosionLevel[$y][$x] % 3;
    }

    private function resolveGeologicIndex(int $x, int $y): int
    {
        if ($x === 0 && $y === 0) {
            return 0;
        }
        if ($x === $this->target->x && $y === $this->target->y) {
            return 0;
        }
        if ($y === 0) {
            return $x * 16807;
        }
        if ($x === 0) {
            return $y * 48271;
        }
        return $this->erosionLevel[$y][$x - 1] * $this->erosionLevel[$y - 1][$x];
    }
}