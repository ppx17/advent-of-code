<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 11:42
 */

namespace Ppx17\Aoc2018\Days\Day20;


class NodeCollection
{
    private $collection = [];

    public function addNode(Node $node)
    {
        $this->collection[$node->id()] = $node;
    }

    public function findNeighbor(Node $location, $character): ?Node
    {
        return $this->collection[$location->newNode($character)->id()] ?? null;
    }

    public function distances(Node $startPoint): array
    {
        $visited = [];
        $distances = [];

        $neighbors = new \SplQueue();
        $neighbors->push([0, $startPoint]);

        while (!$neighbors->isEmpty()) {
            list($nodeDistance, $node) = $neighbors->pop();
            /** @var $node Node */
            if (isset($visited[$node->id()])) {
                continue;
            }
            $visited[$node->id()] = $node;
            $distances[$node->id()] = $nodeDistance;
            foreach ($node->links() as $link) {
                $neighbors->push([$nodeDistance + 1, $link]);
            }

        }

        return $distances;
    }
}