<?php

namespace Ppx17\Aoc2018\Days\Day6;


use Ppx17\Aoc2018\Days\Common\Vector;
use Ppx17\Aoc2018\Days\Day;

class Day6 extends Day
{
    /**
     * @var CoordinateCollection
     */
    private $collection;

    private $counts;
    private $withinDistanceLimits;

    public function __construct(string $data)
    {
        parent::__construct($data);

        $this->createCoordinateCollectionFromData();
        $this->solve();
    }

    public function part1(): string
    {
        return (string)max($this->counts);
    }

    public function part2(): string
    {
        return $this->withinDistanceLimits;
    }

    private function createCoordinateCollectionFromData(): void
    {
        $coordinates = [];
        preg_match_all(
            "/(?<x>[0-9]+), (?<y>[0-9]+)/",
            $this->data,
            $coordinates,
            PREG_SET_ORDER);

        $this->collection = new CoordinateCollection();
        for ($i = 0; $i < count($coordinates); $i++) {
            $this->collection->addCoordinate(new Vector($coordinates[$i]['x'], $coordinates[$i]['y']));
        }
    }

    private function solve(): void
    {
        $edgeIds = [];
        $this->counts = [];
        $this->withinDistanceLimits = 0;
        for ($x = $this->collection->topLeft->x; $x <= $this->collection->bottomRight->x; $x++) {
            for ($y = $this->collection->topLeft->y; $y <= $this->collection->bottomRight->y; $y++) {
                $currentVector = new Vector($x, $y);
                $id = $this->collection->closestId($currentVector);
                $this->counts[$id]++;
                if($this->collection->isTotalDistanceBelow($currentVector)) {
                    $this->withinDistanceLimits++;
                }
                if (
                    !isset($edgeIds[$id]) && $this->collection->isEdge($currentVector)
                ) {
                    $edgeIds[$id] = true;
                }
            }
        }

        // Everything touching an edge will be infinite, so should be removed
        foreach (array_keys($edgeIds) as $id) {
            unset($this->counts[$id]);
        }
    }
}