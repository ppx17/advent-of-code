<?php

namespace Ppx17\Aoc2018\Days\Day22;


use Ppx17\Aoc2018\Days\Common\AStar\AStarNode;
use Ppx17\Aoc2018\Days\Common\AStar\BaseNode;
use Ppx17\Aoc2018\Days\Common\Vector;

class Node extends BaseNode implements AStarNode
{

    private $gear;
    private $position;
    private $id;

    public static function create(Vector $position, string $gear, ?self $parent)
    {
        return (new self($position, $gear))->setParent($parent);
    }

    public function __construct(Vector $position, string $gear)
    {
        $this->position = $position;
        $this->gear = $gear;
    }

    public function getID(): string
    {
        if($this->id === null) {
            $this->id = sprintf('%s,%s,%s', $this->position->x, $this->position->y, $this->gear);
        }
        return $this->id;
    }

    /**
     * @return Vector
     */
    public function getPosition(): Vector
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getGear(): string
    {
        return $this->gear;
    }
}