<?php

namespace Ppx17\Aoc2018\Days\Day15;

use Ppx17\Aoc2018\Days\Common\AStar\AStarNode;
use Ppx17\Aoc2018\Days\Common\AStar\NodeGenerator;
use Ppx17\Aoc2018\Days\Common\AStar\UniqueNodeList;

class MapNodeGenerator implements NodeGenerator
{
    private $map;

    private $target;

    /**
     * MapNodeGenerator constructor.
     * @param Map $map
     * @param Vector|null $target Can be left blank when used with Dijkstra, needed for AStar
     */
    public function __construct(Map $map, ?Vector $target)
    {
        $this->map = $map;
        $this->target = $target;
    }

    public function generateAdjacentNodes(AStarNode $node): UniqueNodeList
    {
        /** @var MapNode $node */
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

    /**
     * Unused in Dijkstra's, only for AStar.
     * @param AStarNode $start
     * @param AStarNode $end
     * @return int
     */
    public function calculateEstimatedCost(AStarNode $start, AStarNode $end): int
    {
        return 0;
    }
}