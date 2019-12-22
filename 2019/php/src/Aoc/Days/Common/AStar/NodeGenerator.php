<?php

namespace Ppx17\Aoc2019\Aoc\Days\Common\AStar;


interface NodeGenerator
{
    public function generateAdjacentNodes(AStarNode $node): UniqueNodeList;
    public function calculateRealCost(AStarNode $node, AStarNode $adjacent): int;
    public function calculateEstimatedCost(AStarNode $start, AStarNode $end): int;
}
