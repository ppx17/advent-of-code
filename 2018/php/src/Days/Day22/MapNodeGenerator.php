<?php

namespace Ppx17\Aoc2018\Days\Day22;


use Ppx17\Aoc2018\Days\Common\AStar\AStarNode;
use Ppx17\Aoc2018\Days\Common\AStar\NodeGenerator;
use Ppx17\Aoc2018\Days\Common\AStar\UniqueNodeList;
use Ppx17\Aoc2018\Days\Common\Vector;

class MapNodeGenerator implements NodeGenerator
{
    private $map;
    private $manhattanDistancePower;

    public function __construct(Map $map, int $manhattanDistancePower)
    {
        $this->map = $map;
        $this->manhattanDistancePower = $manhattanDistancePower;
    }

    public function generateAdjacentNodes(AStarNode $node): UniqueNodeList
    {
        /** @var Node $node */
        $list = new UniqueNodeList();
        if ($node->getPosition()->y > 1) {
            $this->addNeighborsToList($list, $node, $node->getPosition()->up());
        }
        if ($node->getPosition()->x > 1) {
            $this->addNeighborsToList($list, $node, $node->getPosition()->left());
        }
        $this->addNeighborsToList($list, $node, $node->getPosition()->down());
        $this->addNeighborsToList($list, $node, $node->getPosition()->right());
        return $list;
    }

    public function calculateRealCost(AStarNode $node, AStarNode $adjacent): int
    {
        /** @var Node $node */
        /** @var Node $adjacent */
        $gearChangeCost = ($node->getGear() === $adjacent->getGear()) ? 0 : 7;
        $stepCost = ($node->getPosition()->manhattanDistance($adjacent->getPosition()) === 1) ? 1 : 10000000;
        return $gearChangeCost + $stepCost;
    }

    public function calculateEstimatedCost(AStarNode $start, AStarNode $end): int
    {
        /** @var Node $start */
        /** @var Node $end */
        return $start->getPosition()->manhattanDistance($end->getPosition()) ^ $this->manhattanDistancePower;
    }

    private function addNeighborsToList(UniqueNodeList $list, Node $node, Vector $neighborPosition): void
    {
        $terrain = $this->map->getTypeByVector($neighborPosition);

        if ($terrain === null) {
            return;
        }

        if ($terrain === 0) {
            // rocky
            $list->add(Node::create($neighborPosition, 'C', $node));
            $list->add(Node::create($neighborPosition, 'T', $node));
        } elseif ($terrain === 1) {
            // wet
            $list->add(Node::create($neighborPosition, 'C', $node));
            $list->add(Node::create($neighborPosition, '', $node));
        } elseif ($terrain === 2) {
            // narrow
            $list->add(Node::create($neighborPosition, '', $node));
            $list->add(Node::create($neighborPosition, 'T', $node));
        }
    }
}