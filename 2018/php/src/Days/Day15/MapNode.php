<?php

namespace Ppx17\Aoc2018\Days\Day15;


use Ppx17\Aoc2018\Days\Common\AStar\BaseNode;

class MapNode extends BaseNode
{
    private $id;
    private $location;

    public function __construct(Vector $position)
    {
        $this->location = $position;
    }

    public function getID(): string
    {
        if ($this->id === null) {
            $this->id = sprintf("%s,%s", $this->location->x, $this->location->y);
        }
        return $this->id;
    }

    public function getLocation(): Vector
    {
        return $this->location;
    }
}