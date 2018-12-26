<?php
namespace Ppx17\Aoc2018\Days\Common\AStar;


class Dijkstra
{
    private $openList;
    private $closedList;
    private $nodeProvider;

    public function __construct(NodeGenerator $nodeProvider)
    {
        $this->openList = new PriorityNodeList();
        $this->closedList = new UniqueNodeList();
        $this->nodeProvider = $nodeProvider;
    }

    public function getOpenList(): PriorityNodeList
    {
        return $this->openList;
    }

    public function getClosedList(): UniqueNodeList
    {
        return $this->closedList;
    }

    public function clear(): void
    {
        $this->getOpenList()->clear();
        $this->getClosedList()->clear();
    }

    public function run(AStarNode $start, array $goals): array
    {
        $goalIds = array_map(function(AStarNode $node) { return $node->getID(); }, $goals);

        $this->clear();

        $start->setG(0);

        $this->getOpenList()->add($start, 0);

        $foundG = null;
        $foundPaths = [];

        while (!$this->getOpenList()->isEmpty()) {
            $currentNode = $this->getOpenList()->extractBest();

            if($foundG !== null && $currentNode->getG() > $foundG) {
                return $foundPaths;
            }

            $this->getClosedList()->add($currentNode);

            if (in_array($currentNode->getID(), $goalIds)) {
                $foundPaths[] = $this->generatePathFromStartNodeTo($currentNode);
                $foundG = $currentNode->getG();
                continue;
            }

            $successors = $this->computeAdjacentNodes($currentNode);

            foreach ($successors as $successor) {
                /** @var $successor AStarNode */
                if ($this->getOpenList()->contains($successor)) {
                    /** @var $successorInOpenList AStarNode */
                    $successorInOpenList = $this->getOpenList()->get($successor);

                    if ($successor->getG() >= $successorInOpenList->getG()) {
                        continue;
                    }
                }

                if ($this->getClosedList()->contains($successor)) {
                    /** @var $successorInClosedList AStarNode */
                    $successorInClosedList = $this->getClosedList()->get($successor);

                    if ($successor->getG() >= $successorInClosedList->getG()) {
                        continue;
                    }
                }

                $successor->setParent($currentNode);

                $this->getClosedList()->remove($successor);

                $this->getOpenList()->add($successor, -$successor->getG());
            }
        }

        return $foundPaths;
    }

    private function generatePathFromStartNodeTo(AStarNode $node): array
    {
        $path = [];

        $currentNode = $node;

        while ($currentNode !== null) {
            array_unshift($path, $currentNode);

            $currentNode = $currentNode->getParent();
        }

        return $path;
    }

    private function computeAdjacentNodes(AStarNode $node): UniqueNodeList
    {
        $nodes = $this->nodeProvider->generateAdjacentNodes($node);

        foreach ($nodes as $adjacentNode) {
            /** @var $adjacentNode AStarNode */
            $adjacentNode->setG($node->getG() + $this->nodeProvider->calculateRealCost($node, $adjacentNode));
        }

        return $nodes;
    }
}