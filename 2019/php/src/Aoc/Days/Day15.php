<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;
use Ppx17\Aoc2019\Aoc\Days\Day11\Map;
use Ppx17\Aoc2019\Aoc\Days\Day15\Direction;
use Ppx17\Aoc2019\Aoc\Days\Day15\Droid;

class Day15 extends AbstractDay
{
    private Map $map;

    private array $tiles = [
        Droid::TILE_WALL => '#',
        Droid::TILE_SPACE => '.',
        Droid::TILE_OXYGEN => 'O',
    ];

    private Collection $directions;
    private Vector $tankLocation;
    private array $visited = [];

    public function dayNumber(): int
    {
        return 15;
    }

    public function setUp(): void
    {
        ini_set('memory_limit', '256M');
        parent::setUp();
        $this->map = new Map(' ');
    }

    public function part1(): string
    {
        $droidThatFoundTank = $this->exploreMap();

        $this->tankLocation = $droidThatFoundTank->location;

        return (string)($droidThatFoundTank->generation + 1);
    }

    public function part2(): string
    {
        $active = [$this->tankLocation];
        for ($tick = 0; $tick < 1000; $tick++) {
            $nextActive = [];
            foreach ($active as $current) {
                /** @var Vector $current */
                $this->directions
                    ->map(fn($x) => $current->add($x))
                    ->filter(fn($loc) => $this->map->color($loc) === '.')
                    ->each(function (Vector $loc) use (&$nextActive) {
                        $nextActive[] = $loc;
                        $this->map->paint($loc, 'O');
                    });
            }
            if (count($nextActive) === 0) {
                return $tick;
            }
            $active = $nextActive;
        }
        return 'tick limit';
    }

    private function exploreMap(): Droid
    {
        $this->directions = collect([
            new Direction(0, 1),
            new Direction(0, -1),
            new Direction(1, 0),
            new Direction(-1, 0),
        ]);

        $droidThatFoundTank = null;

        $initialDroid = new Droid(new Vector(0, 0), new Direction(1, 0), $this->getInputIntCode());

        $droids = new \SplQueue();
        $droids->enqueue($initialDroid);

        while (!$droids->isEmpty()) {
            /** @var Droid $droid */
            $droid = $droids->dequeue();
            $tile = $droid->discover();
            if ($tile->type === Droid::TILE_OXYGEN) {
                $droidThatFoundTank = $droid;
            } elseif ($tile->type === Droid::TILE_SPACE) {
                $this->map->paint($tile->location, '.');
            } else {
                $this->map->paint($tile->location, $this->tiles[$tile->type]);
            }
            $this->unexploredNeighbors($droid)->each(fn(Direction $dir) => $droids->enqueue($droid->spawn($dir)));
        }

        return $droidThatFoundTank;
    }

    private function unexploredNeighbors(Droid $droid)
    {
        return $this
            ->directions
            ->filter(fn($direction) => $this->tileInDirection($droid, $direction) === ' ')
            ->reject(fn($direction) => $this->visited($droid, $direction));
    }

    private function tileInDirection(Droid $droid, Direction $direction)
    {
        return $this->map->color($droid->location->add($direction));
    }

    private function visited(Droid $droid, $direction)
    {
        $key = (string)$droid->location->add($direction);
        $result = isset($this->visited[$key]);
        $this->visited[$key] = true;
        return $result;
    }
}
