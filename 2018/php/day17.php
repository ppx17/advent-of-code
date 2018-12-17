<?php

namespace Aoc2018\Day17;
$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");

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

    public function up(): Vector
    {
        return new Vector($this->x, $this->y - 1);
    }

    public function down(): Vector
    {
        return new Vector($this->x, $this->y + 1);
    }

    public function left(): Vector
    {
        return new Vector($this->x - 1, $this->y);
    }

    public function right(): Vector
    {
        return new Vector($this->x + 1, $this->y);
    }
}

class Map
{
    public $minX = PHP_INT_MAX;
    public $maxX = PHP_INT_MIN;
    public $minY = PHP_INT_MAX;
    public $maxY = PHP_INT_MIN;
    private $grid;

    public function __construct()
    {
        $this->grid = [];
    }

    public function addVein(Vector $start, Vector $end): void
    {
        $this->setBetween($start, $end, '#');
        $this->maxX = max($this->maxX, $end->x);
        $this->minX = min($this->minX, $start->x);

        $this->minY = min($this->minY, $start->y);
        $this->maxY = max($this->maxY, $end->y);
    }

    public function setBetween(Vector $start, Vector $end, string $symbol): void
    {
        for ($y = $start->y; $y <= $end->y; $y++) {
            for ($x = $start->x; $x <= $end->x; $x++) {
                $this->grid[$y][$x] = $symbol;
            }
        }
    }

    public function print(): void
    {
        for ($y = 0; $y <= $this->maxY; $y++) {
            for ($x = $this->minX - 1; $x <= $this->maxX + 1; $x++) {
                if ($y === 0) {
                    if ($x === 500) {
                        echo '+';
                    } else {
                        echo ' ';
                    }
                } else {
                    echo $this->grid[$y][$x] ?? '.';
                }
            }
            echo PHP_EOL;
        }
    }

    public function isOutside(Vector $location): bool
    {
        return $location->y > $this->maxY;
    }

    public function isFree(Vector $location): bool
    {
        $symbol = $this->grid[$location->y][$location->x] ?? null;
        return $symbol === null || $symbol === '|';
    }

    public function set(Vector $location, string $symbol): void
    {
        $this->grid[$location->y][$location->x] = $symbol;
    }

    public function setRunningWater(Vector $location): void
    {
        $this->set($location, '|');
    }

    public function setStillWater(Vector $location): void
    {
        $this->set($location, '~');
    }

    public function countWater(): int
    {
        $water = 0;
        for ($y = $this->minY; $y <= $this->maxY; $y++) {
            for ($x = $this->minX -1; $x <= $this->maxX + 1; $x++) {
                if (isset($this->grid[$y][$x]) && ($this->grid[$y][$x] === '~' || $this->grid[$y][$x] === '|')) {
                    $water++;
                }
            }
        }
        return $water;
    }

    public function countStillWater(): int
    {
        $water = 0;
        for ($y = $this->minY; $y <= $this->maxY; $y++) {
            for ($x = $this->minX -1; $x <= $this->maxX + 1; $x++) {
                if (isset($this->grid[$y][$x]) && ($this->grid[$y][$x] === '~')) {
                    $water++;
                }
            }
        }
        return $water;
    }
}

class Simulator
{
    private $queue;
    private $map;
    private $onQueue = [];

    public function __construct(Map $map, Vector $start)
    {
        $this->map = $map;
        $this->queue = new \SplQueue();
        $this->queue($start);
    }

    public function simulate()
    {
        while (!$this->queue->isEmpty()) {
            $this->simulateStep();
        }
    }

    private function simulateStep()
    {
        $current = $this->dequeue();

        if ($this->map->isFree($current->down())) {
            $this->map->setRunningWater($current);
            $this->queue($current->down());
            return;
        }

        $right = $current;
        $boxedRight = true;
        do {
            $right = $right->right();
            if ($this->map->isFree($right->down())) {
                // We can start falling again
                $this->queue($right->down());
                $boxedRight = false;
                break;
            }
        } while ($this->map->isFree($right) && $boxedRight);

        $left = $current;
        $boxedLeft = true;
        do {
            $left = $left->left();
            if ($this->map->isFree($left->down())) {
                // We can start falling again
                $this->queue($left->down());
                $boxedLeft = false;
                break;
            }
        } while ($this->map->isFree($left) && $boxedLeft);

        if ($boxedLeft && $boxedRight) {
            $this->map->setBetween($left->right(), $right->left(), '~');
            $this->queue($current->up());
        } else {
            $this->map->setBetween(
                (($boxedLeft) ? $left->right() : $left),
                (($boxedRight) ? $right->left() : $right),
                '|');
        }
    }

    private function queue(Vector $step): void
    {
        $index = $step->y * $this->map->maxY + $step->x;
        if (!isset($this->onQueue[$index])) {
            if (!$this->map->isOutside($step)) {
                $this->queue->enqueue($step);
                $this->onQueue[$index] = true;
            }
        }
    }

    private function dequeue(): Vector
    {
        $current = $this->queue->dequeue();
        $index = $current->y * $this->map->maxY + $current->x;
        unset($this->onQueue[$index]);
        return $current;
    }
}

preg_match_all('#x=(?<x>\d+), y=(?<y1>\d+)..(?<y2>\d+)#m',
    $data, $vertical_veins, PREG_SET_ORDER
);
preg_match_all('#y=(?<y>\d+), x=(?<x1>\d+)..(?<x2>\d+)#m',
    $data, $horizontal_veins, PREG_SET_ORDER
);

$map = new Map();
foreach ($vertical_veins as $vein) {
    $map->addVein(new Vector($vein['x'], $vein['y1']), new Vector($vein['x'], $vein['y2']));
}
foreach ($horizontal_veins as $vein) {
    $map->addVein(new Vector($vein['x1'], $vein['y']), new Vector($vein['x2'], $vein['y']));
}

$start = new Vector(500, 1);

$simulator = new Simulator($map, $start);
$simulator->simulate();


echo "Part 1: " . $map->countWater() . PHP_EOL;
echo "Part 2: " . $map->countStillWater() . PHP_EOL;
