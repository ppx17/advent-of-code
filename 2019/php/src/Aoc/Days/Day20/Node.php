<?php

namespace Ppx17\Aoc2019\Aoc\Days\Day20;


use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Days\Common\AStar\AStarNode;
use Ppx17\Aoc2019\Aoc\Days\Common\AStar\BaseNode;
use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;

class Node extends BaseNode implements AStarNode
{
    public Collection $keys;
    public int $level = 0;
    public string $lastPortal = '';
    private Vector $position;

    public function __construct(Vector $position)
    {
        $this->position = $position;
        $this->keys = new Collection();
    }

    public static function create(Vector $position, ?self $parent): self
    {
        return (new self($position))->setParent($parent);
    }

    public function getID(): string
    {
        return ((string)$this->position) . ':' . $this->level;
    }

    /**
     * @return Vector
     */
    public function getPosition(): Vector
    {
        return $this->position;
    }
}
