<?php

namespace Ppx17\Aoc2018\Days\Day15;


use Ppx17\Aoc2018\Days\Common\AStar\AStar;

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
}