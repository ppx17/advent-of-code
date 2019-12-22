<?php

namespace Ppx17\Aoc2019\Aoc\Days\Day20;


use Ppx17\Aoc2019\Aoc\Days\Common\AStar\AStarNode;
use Ppx17\Aoc2019\Aoc\Days\Common\AStar\NodeGenerator;
use Ppx17\Aoc2019\Aoc\Days\Common\AStar\UniqueNodeList;
use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;

class MapNodeGeneratorRecursive extends BaseNodeGenerator implements NodeGenerator
{
    private array $outerPortals = [];

    public function calculateEstimatedCost(AStarNode $start, AStarNode $end): int
    {
        // Tuned the algorithm by making traversing to deeper levels very expensive.
        return $start->getPosition()->manhattanTo($end->getPosition()) + (abs($start->level - $end->level) * 512);
    }

    public function setPortal(string $label, Vector $location)
    {
        parent::setPortal($label, $location);
        if ($label !== 'AA' && $label !== 'ZZ') {
            if ($location->x === 2
                || $location->y === 2
                || $location->x === $this->mapWidth - 3
                || $location->y === $this->mapHeight - 3) {
                $this->outerPortals[(string)$location] = true;
            }
        }
    }

    protected function addNeighborsToList(UniqueNodeList $list, Node $parent, Vector $neighborPosition): void
    {
        $terrain = $this->map->color($neighborPosition);

        if ($terrain === '#') {
            // Dont walk into walls
            return;
        }

        if ($terrain === '.') {
            $next = Node::create($neighborPosition, $parent);
            $next->level = $parent->level;
            $next->lastPortal = $parent->lastPortal;
            $list->add($next);
            // Do walk onto .'s
            return;
        }

        $level = $parent->level;

        $portal = $this->portalsByLocation[(string)$parent->getPosition()];
        if ($portal === null) {
            dd($this->portalsByLocation);
        }

        // Don't try to traverse through start or finish
        if ($portal === 'AA' || $portal === 'ZZ') {
            return;
        }

        $isOuterPortal = isset($this->outerPortals[(string)$parent->getPosition()]);

        if ($isOuterPortal && $level === 0) {
            // On top level all outer portals are walls
            return;
        }

        if ($isOuterPortal) {
            // Using outer portal to go up a level
            $level--;
        } else {
            // Using inner portal to go down a level
            $level++;
        }

        $neighborPosition = $this->portalDestinationsByLocation[(string)$parent->getPosition()];

        $next = Node::create($neighborPosition, $parent);
        $next->level = $level;
        $next->lastPortal = $portal;

        $list->add($next);
    }
}

