<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day20;

use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Days\Common\AStar\AStarNode;
use Ppx17\Aoc2019\Aoc\Days\Common\AStar\NodeGenerator;
use Ppx17\Aoc2019\Aoc\Days\Common\AStar\UniqueNodeList;
use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;
use Ppx17\Aoc2019\Aoc\Days\Day15\Direction;
use Ppx17\Aoc2019\Aoc\Days\Day17\Map;

abstract class BaseNodeGenerator implements NodeGenerator
{
    public Collection $portalsByLabel;
    public int $mapWidth = 0;
    public int $mapHeight = 0;
    protected Map $map;
    protected Collection $portalsByLocation;
    protected Collection $directions;
    protected array $portalDestinationsByLocation = [];

    public function __construct(array $grid)
    {
        $this->map = new Map(' ');
        $this->directions = collect([
            new Direction(0, 1),
            new Direction(0, -1),
            new Direction(1, 0),
            new Direction(-1, 0),
        ]);

        $this->portalsByLabel = new Collection();
        $this->portalsByLocation = new Collection();

        $this->parseGrid($grid);
    }

    public function calculateRealCost(AStarNode $node, AStarNode $adjacent): int
    {
        return 1;
    }

    public function getPortal(string $label): ?Node
    {
        return $this->portalsByLabel->has($label)
            ? new Node($this->portalsByLabel->get($label))
            : null;
    }

    public function setPortal(string $label, Vector $location)
    {
        $this->portalsByLocation[(string)$location] = $label;
        if ($this->portalsByLabel->has($label)) {
            $other = $this->portalsByLabel->get($label);
            $this->portalDestinationsByLocation[(string)$location] = $other;
            $this->portalDestinationsByLocation[(string)$other] = $location;
        } else {
            $this->portalsByLabel->put($label, $location);
        }
    }

    public function generateAdjacentNodes(AStarNode $node): UniqueNodeList
    {
        /** @var Node $node */
        $list = new UniqueNodeList();

        $this
            ->directions
            ->map(function ($direction) use ($node) {
                return $node->getPosition()->add($direction);
            })
            ->each(fn($x) => $this->addNeighborsToList($list, $node, $x));

        return $list;
    }

    private function parseGrid(array $grid)
    {
        $this->mapHeight = count($grid) - 1; // Account for last non-trimmed newline
        $this->mapWidth = count($grid[2]) + 2;

        foreach ($grid as $y => $line) {
            foreach ($grid[$y] as $x => $tile) {
                if ($tile === ' ') {
                    continue;
                }
                if ($tile === '#' || $tile === '.') {
                    $this->map->paint(new Vector($x, $y), $tile);
                    continue;
                }
                if ($this->isLabel($grid[$y][$x + 1] ?? ' ')) {
                    $label = $tile . $grid[$y][$x + 1];
                    $vector = (($grid[$y][$x + 2] ?? ' ') === '.')
                        ? new Vector($x + 2, $y)
                        : new Vector($x - 1, $y);
                    $this->setPortal($label, $vector);
                    continue;
                }
                if ($this->isLabel($grid[$y + 1][$x] ?? ' ')) {
                    $label = $tile . $grid[$y + 1][$x];
                    $vector = (($grid[$y + 2][$x] ?? ' ') === '.')
                        ? new Vector($x, $y + 2)
                        : new Vector($x, $y - 1);
                    $this->setPortal($label, $vector);
                }
            }
        }
    }

    private function isLabel(string $tile)
    {
        return $tile >= 'A' && $tile <= 'Z';
    }
}
