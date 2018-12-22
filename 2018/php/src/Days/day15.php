<?php

namespace Aoc2018\Day15;

// TODO: Finish part 2
use JMGQ\AStar\AbstractNode;
use JMGQ\AStar\AStar as BaseAStar;
use JMGQ\AStar\Node;

$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");
require_once 'helpers.php';
require_once 'vendor/autoload.php';

class Vector
{
    /**
     * @var int x
     */
    public $x;
    /**
     * @var int y
     */
    public $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Get neighbors in reading order (top, left, right, bottom)
     * @return array
     */
    public function neighbors(): array
    {
        $neighbors = [];
        if ($this->y > 1) {
            $neighbors[] = new Vector($this->x, $this->y - 1);
        }
        if ($this->x > 1) {
            $neighbors[] = new Vector($this->x - 1, $this->y);
        }
        $neighbors[] = new Vector($this->x + 1, $this->y);
        $neighbors[] = new Vector($this->x, $this->y + 1);

        return $neighbors;
    }

    public function manhattanDistance(Vector $other)
    {
        return abs($this->x - $other->x) + abs($this->y - $other->y);
    }

    public function equals(Vector $other)
    {
        return $this->x === $other->x && $this->y === $other->y;
    }
}

class Sort
{
    public static function unitsByReadingOrder(array &$units)
    {
        usort($units, function (Unit $a, Unit $b) {
            if ($a->location->y !== $b->location->y) {
                return $a->location->y - $b->location->y;
            }
            return $a->location->x - $b->location->x;
        });
    }

    public static function enemiesByHitPoints(array &$units)
    {
        usort($units, function (Unit $a, Unit $b) {
            return $a->hitPoints - $b->hitPoints;
        });
    }

    public static function pathsByLengthAndReadingOrder(array &$paths)
    {
        usort($paths, function (array $a, array $b) {
            $countA = count($a);
            $countB = count($b);
            if ($countA != $countB) {
                // Paths not same length, prefer shortest path.
                return $countA - $countB;
            }

            // Paths of same length, choose start in reading order
            if (!$a[0]->getLocation()->equals($b[0]->getLocation())) {
                if ($a[0]->getLocation()->y !== $b[0]->getLocation()->y) {
                    // first top down...
                    return $a[0]->getLocation()->y - $b[0]->getLocation()->y;
                }
                // Then left to right
                return $a[0]->getLocation()->x - $b[0]->getLocation()->x;

            }

            // Starting position is equal, sort destination in reading order
            if ($a[$countA - 1]->getLocation()->y !== $b[$countB - 1]->getLocation()->y) {
                // top down..
                return $a[$countB - 1]->getLocation()->y - $b[$countB - 1]->getLocation()->y;
            }

            // left to right
            return $a[$countB - 1]->getLocation()->x - $b[$countB - 1]->getLocation()->x;

        });
    }
}

class Map
{
    public $allUnits;
    public $unitsByLocation;
    public $elves;
    public $goblins;
    private $walls;

    private $elfStrength;

    private $width;
    private $height;

    public function __construct(string $map, int $elfStrength = 3)
    {
        $this->elfStrength = $elfStrength;
        $this->loadMap($map);
    }

    public function isOccupied(Vector $vector): bool
    {
        return $vector->x < 1 || $vector->y < 1 ||
            $vector->y > $this->height ||
            $vector->x > $this->width ||
            ($this->walls[$vector->y][$vector->x] === true) ||
            $this->unitsByLocation[$vector->y][$vector->x] !== null;
    }

    public function print()
    {
        $grid = [];
        foreach ($this->walls as $y => $row) {
            foreach ($row as $x => $col) {
                $grid[$y][$x] = ($col === true) ? '#' : '.';
            }
        }
        foreach ($this->allUnits as $unit) {
            $grid[$unit->location->y][$unit->location->x] = $unit->type;
        }

        foreach ($grid as $row) {
            echo implode('', $row) . PHP_EOL;
        }
    }

    public function unitAt(Vector $location): ?Unit
    {
        return $this->unitsByLocation[$location->y][$location->x] ?? null;
    }

    public function neighborsFrom(Vector $location): array
    {
        $result = [];
        foreach ($location->neighbors() as $position) {
            $unit = $this->unitAt($position);
            if ($unit !== null) {
                $result[] = $unit;
            }
        }
        return $result;
    }

    public function unitDies(Unit $unit)
    {
        unset($this->unitsByLocation[$unit->location->y][$unit->location->x]);
        $this->removeFromArray($this->allUnits, $unit);

        if ($unit->type === 'E') {
            $this->removeFromArray($this->elves, $unit);
        } elseif ($unit->type === 'G') {
            $this->removeFromArray($this->goblins, $unit);
        }
    }

    public function moveUnit(Unit $unit, Vector $newLocation)
    {
        unset($this->unitsByLocation[$unit->location->y][$unit->location->x]);
        $this->unitsByLocation[$newLocation->y][$newLocation->x] = $unit;
        $unit->location = $newLocation;
    }

    private function removeFromArray(array &$array, Unit $unit)
    {
        array_splice($array, array_search($unit, $array), 1);
    }

    private function setMap(int $x, int $y, string $char)
    {
        $this->walls[$y][$x] = ($char === '#');
        if ($char === 'E' || $char === 'G') {
            $this->setUnit($x, $y, $char);
        }
    }

    private function setUnit(int $x, int $y, string $char): void
    {
        $Unit = new Unit(new Vector($x, $y), $char);

        $this->allUnits[] = $Unit;
        $this->unitsByLocation[$y][$x] = $Unit;

        if ($char === 'E') {
            $Unit->attackPower = $this->elfStrength;
            $this->elves[] = $Unit;
        } else {
            $this->goblins[] = $Unit;
        }
    }

    private function loadMap(string $map): void
    {
        $this->walls = [];
        $this->goblins = [];
        $this->elves = [];
        $this->allUnits = [];

        $rows = explode("\n", $map);

        // Remove top and bottom since they're always wall
        array_shift($rows);
        array_pop($rows);

        $this->height = count($rows);
        $this->width = strlen($rows[0]) - 2;

        foreach ($rows as $y => $row) {
            for ($x = 1; $x < strlen($row) - 1; $x++) {
                $this->setMap($x, $y + 1, $row[$x]);
            }
        }
    }
}

class Unit
{
    public $hitPoints;
    public $location;
    public $type;
    public $attackPower;

    public function __construct(Vector $location, string $type)
    {
        $this->type = $type;
        $this->hitPoints = 200;
        $this->attackPower = 3;
        $this->location = $location;
    }

    public function isElf()
    {
        return $this->type === 'E';
    }

    public function isGoblin()
    {
        return $this->type === 'G';
    }
}

class ElfDiedException extends \Exception
{

}

class Simulator
{
    private $map;
    private $elvesMustStayAlive = false;

    public function __construct(Map $map, bool $elvesMustStayAlive = false)
    {
        $this->map = $map;
        $this->elvesMustStayAlive = $elvesMustStayAlive;
    }

    /**
     * @param bool $printMap
     * @return int
     * @throws ElfDiedException
     */
    public function simulate(bool $printMap = true): int
    {
        for ($round = 1; $round < 100; $round++) {

            if (!$this->simulateRound()) {
                // Round ended halfway
                if ($printMap) {
                    $this->map->print();
                }
                return $this->finishFight($round - 1);
            } else {
                // Round played until end
                if ($this->oneSideRemaining()) {
                    if ($printMap) {
                        $this->map->print();
                    }
                    return $this->finishFight($round);
                }
            }
            if ($printMap) {
                echo PHP_EOL;
                $this->map->print();
            }
        }
    }

    /**
     * Returns true when round finished, false when a side won halfway
     *
     * @return bool
     * @throws ElfDiedException
     */
    private function simulateRound(): bool
    {
        Sort::unitsByReadingOrder($this->map->allUnits);

        foreach ($this->map->allUnits as $unit) {
            if ($this->oneSideRemaining()) {
                return false;
            }
            $this->unitTurn($unit);
        }
        return true;
    }

    /**
     * @param Unit $unit
     * @throws ElfDiedException
     */
    private function unitTurn(Unit $unit): void
    {
        if (!$this->map->isOccupied($unit->location)) {
            // Unit has died, but not removed from iterator yet
            return;
        }
        // Determine if next to enemies
        $directEnemies = $this->directEnemies($unit);

        if (count($directEnemies) > 0) {
            $this->unitFight($unit, $directEnemies);
        } else {
            $this->moveToEnemy($unit);
            $directEnemies = $this->directEnemies($unit);
            if (count($directEnemies) > 0) {
                $this->unitFight($unit, $directEnemies);
            }
        }
    }

    /**
     * @param Unit $unit
     * @param array $enemies
     * @throws ElfDiedException
     */
    private function unitFight(Unit $unit, array $enemies): void
    {
        Sort::enemiesByHitPoints($enemies);

        $this->unitHitBy($enemies[0], $unit);
    }

    private function moveToEnemy(Unit $unit): void
    {
        if ($unit->isElf()) {
            $enemies = $this->map->goblins;
        } else {
            $enemies = $this->map->elves;
        }

        $paths = [];
        foreach ($enemies as $key => $enemy) {
            $possiblePaths = $this->pathsToEnemy($unit, $enemy);
            if (count($possiblePaths) > 0) {
                $paths = array_merge($paths, $possiblePaths);
            }
        }

        if (count($paths) == 0) {
            return;
        }
        //TODO: First select all paths with their destination closest and in reading order, then sort.
        Sort::pathsByLengthAndReadingOrder($paths);

        $this->map->moveUnit($unit, $paths[0][0]->getLocation());
    }

    /**
     * @param Unit $target
     * @param Unit $attacker
     * @throws ElfDiedException
     */
    private function unitHitBy(Unit $target, Unit $attacker)
    {
        $target->hitPoints -= $attacker->attackPower;

        if ($target->hitPoints <= 0) {
            if ($this->elvesMustStayAlive && $target->isElf()) {
                throw new ElfDiedException();
            }
            $this->map->unitDies($target);
        }
    }

    private function pathsToEnemy(Unit $unit, $enemy): array
    {
        $unitNeighbors = $unit->location->neighbors();

        $enemyNeighbors = $enemy->location->neighbors();
        $paths = [];
        foreach ($unitNeighbors as $unitNeighbor) {
            if (!$this->map->isOccupied($unitNeighbor)) {
                foreach ($enemyNeighbors as $enemyNeighbor) {
                    if (!$this->map->isOccupied($enemyNeighbor)) {
                        $start = new AStarNode($unitNeighbor);
                        $dest = new AStarNode($enemy->location);
                        $aStar = new AStar($this->map, $enemy->location);
                        $route = $aStar->run($start, $dest);
                        if (count($route) > 0) {
                            $paths[] = $route;
                        }
                    }
                }
            }
        }
        return $paths;
    }

    private function finishFight(int $round): int
    {
        $hitPointsLeft = 0;
        foreach ($this->map->allUnits as $alive) {
            $hitPointsLeft += $alive->hitPoints;
        }

        return ($round * $hitPointsLeft);
    }

    /**
     * @return bool
     */
    private function oneSideRemaining(): bool
    {
        return count($this->map->elves) == 0 || count($this->map->goblins) == 0;
    }

    /**
     * @param Unit $unit
     * @return array
     */
    private function directEnemies(Unit $unit): array
    {
        return array_filter($this->map->neighborsFrom($unit->location), function ($neighbor) use ($unit) {
            return $neighbor->type !== $unit->type;
        });
    }
}

class AStar extends BaseAStar
{
    private $map;

    private $target;

    public function __construct(Map $map, Vector $target)
    {
        $this->map = $map;
        $this->target = $target;
    }

    public function generateAdjacentNodes(Node $node)
    {
        $neighbors = $node->getLocation()->neighbors();
        $result = [];
        foreach ($neighbors as $neighbor) {
            if ($this->target->manhattanDistance($neighbor) == 0 || !$this->map->isOccupied($neighbor)) {
                $result[] = new AStarNode($neighbor);
            }
        }

        return $result;
    }

    public function calculateRealCost(Node $node, Node $adjacent)
    {
        return 1;
    }

    public function calculateEstimatedCost(Node $start, Node $end)
    {
        return $start->getLocation()->distance($end->getLocation());
    }
}

class AStarNode extends AbstractNode
{
    private $location;

    public function __construct(Vector $location)
    {
        $this->location = $location;
    }

    public function getID()
    {
        return $this->location->x . ',' . $this->location->y;
    }

    public function getLocation(): Vector
    {
        return $this->location;
    }
}

//$map = new Map($data);
//$simulator = new Simulator($map);
//echo "Part 1: " . $simulator->simulate(false) . PHP_EOL;

/*
$power = 34;
debug("Testing %s", $power);
try {
    $map = new Map($data, $power);
    $simulator = new Simulator($map, true);
    echo "Part 2: " . $simulator->simulate(false) . PHP_EOL;
} catch (ElfDiedException $ex) {
    debug("Elf died with power %s", $power);
}
*/

// 57800 too high
// not 59245