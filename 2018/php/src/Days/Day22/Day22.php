<?php

namespace Ppx17\Aoc2018\Days\Day22;

use Ppx17\Aoc2018\Days\Common\AStar\AStar;
use Ppx17\Aoc2018\Days\Common\Vector;
use Ppx17\Aoc2018\Days\Day;

class Day22 extends Day
{
    private $map;
    private $targetPosition;

    public function __construct(string $data)
    {
        parent::__construct($data);

        preg_match("#depth: (?<depth>\d+)\ntarget: (?<x>\d+),(?<y>\d+)#", $this->data, $matches);
        $this->targetPosition = new Vector($matches['x'], $matches['y']);
        $this->map = new Map($this->targetPosition, $matches['depth']);
    }

    public function part1(): string
    {
        return $this->map->riskLevel();
    }

    public function part2(): string
    {
        $start = new Node(new Vector(0, 0), 'T');
        $dest = new Node($this->targetPosition, 'T');


        $limits = new Vector(
             $this->targetPosition->x * 2,
            $this->targetPosition->y * 1.1
        );

        $this->map->setLimit($limits);

        $generator = new MapNodeGenerator($this->map, 8);

        $aStar = new AStar($generator);
        $path = $aStar->run($start, $dest);

        $finalNode = last($path);

        return $finalNode->getG();
    }
}