<?php

namespace Ppx17\Aoc2018\Days\Common\AStar;

class PriorityNodeList
{
    private $nodesById;
    /**
     * @var \SplPriorityQueue
     */
    private $queue;

    public function __construct()
    {
        $this->clear();
    }

    public function add(AStarNode $node, int $priority)
    {
        $this->nodesById[$node->getID()] = $node;
        $this->queue->insert($node, $priority);
    }

    public function isEmpty()
    {
        return $this->queue->isEmpty();
    }

    public function contains(AStarNode $node)
    {
        return isset($this->nodesById[$node->getID()]);
    }

    public function extractBest()
    {
        $bestNode = $this->queue->extract();

        if ($bestNode !== null) {
            $this->remove($bestNode);
        }

        return $bestNode;
    }

    public function get(AStarNode $node)
    {
        return $this->nodesById[$node->getId()] ?? null;
    }

    public function clear()
    {
        $this->nodesById = [];
        $this->queue = new \SplPriorityQueue();
    }

    private function remove(AStarNode $node)
    {
        unset($this->nodesById[$node->getID()]);
    }
}
