<?php
namespace Ppx17\Aoc2019\Aoc\Days\Common\AStar;


class AStar
{
    private PriorityNodeList $openList;
    private UniqueNodeList $closedList;
    private NodeGenerator $nodeProvider;

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

    public function run(AStarNode $start, AStarNode $goal): array
    {
        $path = [];

        $this->clear();

        $start->setG(0);
        $start->setH($this->nodeProvider->calculateEstimatedCost($start, $goal));

        $this->getOpenList()->add($start, -$start->getF());

        while (!$this->getOpenList()->isEmpty()) {
            $currentNode = $this->getOpenList()->extractBest();

            $this->getClosedList()->add($currentNode);

            if ($currentNode->getID() === $goal->getID()) {
                $path = $this->generatePathFromStartNodeTo($currentNode);
                break;
            }

            $successors = $this->computeAdjacentNodes($currentNode, $goal);

            foreach ($successors as $successor) {
                /** @var AStarNode $successor */
                if ($this->getOpenList()->contains($successor)) {
                    /** @var AStarNode $successorInOpenList */
                    $successorInOpenList = $this->getOpenList()->get($successor);

                    if ($successor->getG() >= $successorInOpenList->getG()) {
                        continue;
                    }
                }

                if ($this->getClosedList()->contains($successor)) {
                    $successorInClosedList = $this->getClosedList()->get($successor);

                    if ($successor->getG() >= $successorInClosedList->getG()) {
                        continue;
                    }
                }

                $successor->setParent($currentNode);

                $this->getClosedList()->remove($successor);

                $this->getOpenList()->add($successor, -$successor->getF());
            }
        }

        return $path;
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

    private function computeAdjacentNodes(AStarNode $node, AStarNode $goal): UniqueNodeList
    {
        $nodes = $this->nodeProvider->generateAdjacentNodes($node);

        foreach ($nodes as $adjacentNode) {
            /** @var $adjacentNode AStarNode */
            $adjacentNode->setG($node->getG() + $this->nodeProvider->calculateRealCost($node, $adjacentNode));
            $adjacentNode->setH($this->nodeProvider->calculateEstimatedCost($adjacentNode, $goal));
        }

        return $nodes;
    }
}
