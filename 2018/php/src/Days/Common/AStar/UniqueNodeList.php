<?php

namespace Ppx17\Aoc2018\Days\Common\AStar;

use Traversable;

class UniqueNodeList implements \IteratorAggregate
{
    private $nodesById = [];

    public function add(AStarNode $node)
    {
        $this->nodesById[$node->getID()] = $node;
    }

    public function contains(AStarNode $node)
    {
        return isset($this->nodesById[$node->getID()]);
    }

    public function remove(AStarNode $node)
    {
        unset($this->nodesById[$node->getID()]);
    }

    public function get(AStarNode $node)
    {
        return $this->nodesById[$node->getID()] ?? null;
    }

    public function clear()
    {
        $this->nodesById = [];
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->nodesById);
    }
}
