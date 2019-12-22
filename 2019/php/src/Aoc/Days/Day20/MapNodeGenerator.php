<?php

namespace Ppx17\Aoc2019\Aoc\Days\Day20;


use Ppx17\Aoc2019\Aoc\Days\Common\AStar\AStarNode;
use Ppx17\Aoc2019\Aoc\Days\Common\AStar\NodeGenerator;
use Ppx17\Aoc2019\Aoc\Days\Common\AStar\UniqueNodeList;
use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;

class MapNodeGenerator extends BaseNodeGenerator implements NodeGenerator
{
    public function calculateEstimatedCost(AStarNode $start, AStarNode $end): int
    {
        return 1;
    }

    protected function addNeighborsToList(UniqueNodeList $list, Node $parent, Vector $neighborPosition): void
    {
        $terrain = $this->map->color($neighborPosition);

        if ($terrain === '#') {
            return;
        }

        if ($terrain === ' ') {
            if (!isset($this->portalDestinationsByLocation[(string)$parent->getPosition()])) {
                return;
            }
            $neighborPosition = $this->portalDestinationsByLocation[(string)$parent->getPosition()];
        }

        $next = Node::create($neighborPosition, $parent);


        $list->add($next);
    }
}

