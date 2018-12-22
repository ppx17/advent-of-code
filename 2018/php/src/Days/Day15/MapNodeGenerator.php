<?php

namespace Ppx17\Aoc2018\Days\Day15;

use Ppx17\Aoc2018\Days\Common\AStar\AStarNode;
use Ppx17\Aoc2018\Days\Common\AStar\NodeGenerator;
use Ppx17\Aoc2018\Days\Common\AStar\UniqueNodeList;

class MapNodeGenerator implements NodeGenerator
{
    private $map;

    private $target;

    public function __construct(Map $map, Vector $target)
    {
        $this->map = $map;
        $this->target = $target;
    }

    public function generateAdjacentNodes(AStarNode $node): UniqueNodeList
    {

        $neighbors = $node->getLocation()->neighbors();
        $list = new UniqueNodeList();
        foreach ($neighbors as $neighbor) {
            if (!$this->map->isOccupied($neighbor)) {
                $list->add(new MapNode($neighbor));
            }
        }

        return $list;
    }

    public function calculateRealCost(AStarNode $node, AStarNode $adjacent): int
    {
        return 1;
    }

    public function calculateEstimatedCost(AStarNode $start, AStarNode $end): int
    {
        /** @var MapNode $start */
        /** @var MapNode $end */
        return $start->getLocation()->manhattanDistance($end->getLocation());
    }
}