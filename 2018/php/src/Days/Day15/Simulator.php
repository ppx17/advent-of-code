<?php

namespace Ppx17\Aoc2018\Days\Day15;


use Ppx17\Aoc2018\Days\Common\AStar\AStar;
use Ppx17\Aoc2018\Days\Common\AStar\Dijkstra;

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
        for ($round = 1; $round < 10000; $round++) {

            if (!$this->simulateRound()) {
                // Round ended halfway
                $this->printMapIf($printMap, $round);
                return $this->finishFight($round - 1);
            } else {
                // Round played until end
                if ($this->oneSideRemaining()) {
                    $this->printMapIf($printMap, $round);
                    return $this->finishFight($round);
                }
            }
            $this->printMapIf($printMap, $round);
        }
        throw new \Exception('Round limiter hit');
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

        $targetLocations = [];
        foreach ($enemies as $key => $enemy) {
            /** @var Unit $enemy */
            $neighbors = $enemy->location->neighbors();
            foreach ($neighbors as $neighbor) {
                if (!$this->map->isOccupied($neighbor)) {
                    $targetLocations[] = new MapNode($neighbor);
                }
            }
        }

        $generator = new MapNodeGenerator($this->map, null);
        $pathFinder = new Dijkstra($generator);
        $paths = [];
        foreach($unit->location->neighbors() as $startPoint) {
            if( ! $this->map->isOccupied($startPoint)) {
                $paths = array_merge($paths, $pathFinder->run(new MapNode($startPoint), $targetLocations));
            }
        }

        if (count($paths) == 0) {
            return;
        }
        Sort::pathsByLengthAndReadingOrder($paths);
//
//        if(count($paths) > 1) {
//
//            debug('------ (%s)', count($paths));
//            foreach($paths as $path) {
//                debug("Path length %s,  from %s,%s to %s,%s",
//                    count($path), $path[0]->getLocation()->x, $path[0]->getLocation()->y,
//                    $path[count($path)-1]->getLocation()->x, $path[count($path)-1]->getLocation()->y);
//            }
//            $this->map->print();
//            debug('------');
//        }

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
                        $start = new MapNode($unitNeighbor);
                        $dest = new MapNode($enemy->location);
                        $generator = new MapNodeGenerator($this->map, $enemy->location);
                        $aStar = new AStar($generator);
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

    /**
     * @param bool $printMap
     */
    private function printMapIf(bool $printMap, ?int $round = null): void
    {
        if ($printMap) {
            echo PHP_EOL;
            if($round !== null) {
                debug("Round %s", $round);
            }
            $this->map->print();
        }
    }
}