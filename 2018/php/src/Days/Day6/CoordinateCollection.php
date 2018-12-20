<?php

namespace Ppx17\Aoc2018\Days\Day6;


use Ppx17\Aoc2018\Days\Common\Vector;

class CoordinateCollection
{
    public $list;
    public $topLeft;
    public $bottomRight;

    public function __construct()
    {
        $this->list = [];
        $this->topLeft = new Vector(PHP_INT_MAX, PHP_INT_MAX);
        $this->bottomRight = new Vector(PHP_INT_MIN, PHP_INT_MIN);
    }

    public function addCoordinate(Vector $point): void
    {
        $this->list[] = $point;
        $this->setMinMax($point);
    }

    public function closestId(Vector $point): ?int
    {
        $closestDistance = PHP_INT_MAX;
        $closestId = null;
        foreach ($this->list as $id => $coordinate) {
            $distance = $coordinate->manhattanDistance($point);
            if ($distance < $closestDistance) {
                $closestDistance = $distance;
                $closestId = $id;
            } elseif ($distance === $closestDistance) {
                $closestId = null;
            }
        }
        return $closestId;
    }

    public function isTotalDistanceBelow(Vector $point, int $maxDistance = 10000): bool
    {
        $sum = 0;
        foreach ($this->list as $coordinate) {
            $sum += $coordinate->manhattanDistance($point);
            if ($sum > $maxDistance) {
                return false;
            }
        }
        return true;
    }

    public function isEdge(Vector $point)
    {
        return $point->x === $this->topLeft->x ||
            $point->x === $this->bottomRight->x ||
            $point->y === $this->topLeft->y ||
            $point->y === $this->bottomRight->y;
    }

    private function setMinMax(Vector $point)
    {
        $this->topLeft->x = min($point->x, $this->topLeft->x);
        $this->topLeft->y = min($point->y, $this->topLeft->y);
        $this->bottomRight->x = max($point->x, $this->bottomRight->x);
        $this->bottomRight->y = max($point->y, $this->bottomRight->y);
    }
}