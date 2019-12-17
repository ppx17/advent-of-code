<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;
use Ppx17\Aoc2019\Aoc\Days\Day15\Direction;
use Ppx17\Aoc2019\Aoc\Days\Day15\Droid;
use Ppx17\Aoc2019\Aoc\Days\Day11\Map;

class Day15 extends AbstractDay
{
    private Droid $droid;
    private Map $map;

    private array $tiles = [
        Droid::TILE_WALL => '#',
        Droid::TILE_SPACE => '.',
        Droid::TILE_OXYGEN => 'O',
    ];

    private Collection $directions;
    private Vector $tankLocation;

    public function dayNumber(): int
    {
        return 15;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->droid = new Droid(array_map('intval', explode(',', $this->getInput())));
        $this->map = new Map(' ');

        ini_set('memory_limit', '1024M');
    }

    public function part1(): string
    {
        $droidThatFoundTank = $this->exploreMap();

        $this->tankLocation = $droidThatFoundTank->location;

        return (string)$droidThatFoundTank->generation - 1;
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

        $this->droid->onTileDetected(function (Droid $droid, int $tile, Vector $location) use (
            &$droidThatFoundTank,
            &$droids
        ) {
            $droid->computer->halt();
            if ($tile === Droid::TILE_OXYGEN) {
                $droidThatFoundTank = $droid;
            } elseif ($tile === Droid::TILE_SPACE) {
                $this->map->paint($location, '.');
            } else {
                $this->map->paint($location, $this->tiles[$tile]);
            }

            $this->unexploredNeighbors($droid)->each(fn(Direction $dir) => $droids->enqueue($droid->spawn($dir)));
        });

        $droids = new \SplQueue();
        $droids->enqueue($this->droid);

        while (!$droids->isEmpty()) {
            $droid = $droids->dequeue();
            $droid->computer->run();
        }

        return $droidThatFoundTank;
    }

    private function unexploredNeighbors(Droid $droid)
    {
        return $this
            ->directions
            ->filter(fn($direction) => $this->tileInDirection($droid, $direction) === ' ');
    }

    private function tileInDirection(Droid $droid, Direction $direction)
    {
        return $this->map->color($droid->location->add($direction));
    }
}
