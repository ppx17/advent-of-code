<?php
$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");

preg_match_all(
    "/(?<x>[0-9]+), (?<y>[0-9]+)/",
    $data,
    $coordinates,
    PREG_SET_ORDER);

class CoordinateCollection
{
    private $list;
    private $min = ['x' => PHP_INT_MAX, 'y' => PHP_INT_MAX];
    private $max = ['x' => PHP_INT_MIN, 'y' => PHP_INT_MIN];

    public function __construct()
    {
        $this->list = [];
    }

    public function addCoordinate(int $x, int $y)
    {
        $this->list[] = ['x' => $x, 'y' => $y];
        $this->setMinMax($x, $y);
    }

    private function setMinMax(int $x, int $y)
    {
        if ($x < $this->min['x']) {
            $this->min['x'] = $x;
        }
        if ($y < $this->min['y']) {
            $this->min['y'] = $y;
        }
        if ($x > $this->max['x']) {
            $this->max['x'] = $x;
        }
        if ($y > $this->max['y']) {
            $this->max['y'] = $y;
        }
    }

    public function min(string $index): int
    {
        return $this->min[$index];
    }

    public function max(string $index): int
    {
        return $this->max[$index];
    }

    public function closestId(int $x, int $y): ?int
    {
        $closestDistance = PHP_INT_MAX;
        $closestId = null;
        foreach ($this->list as $id => $coordinate) {
            $distance = $this->distance($coordinate, $x, $y);
            if ($distance < $closestDistance) {
                $closestDistance = $distance;
                $closestId = $id;
            } elseif ($distance === $closestDistance) {
                $closestId = null;
            }
        }
        return $closestId;
    }

    private function distance(array $coordinate, int $x, int $y): int
    {
        return abs($coordinate['x'] - $x) + abs($coordinate['y'] - $y);
    }

    public function isTotalDistanceBelow(int $x, int $y, int $maxDistance = 10000): bool
    {
        $sum = 0;
        foreach ($this->list as $coordinate) {
            $sum += $this->distance($coordinate, $x, $y);
            if ($sum > $maxDistance) {
                return false;
            }
        }
        return true;
    }
}

$collection = new CoordinateCollection();

for ($i = 0; $i < count($coordinates); $i++) {
    $collection->addCoordinate($coordinates[$i]['x'], $coordinates[$i]['y']);
}

$edgeIds = [];
$counts = [];
$withinDistanceLimits = 0;
for ($x = $collection->min('x'); $x <= $collection->max('x'); $x++) {
    for ($y = $collection->min('y'); $y <= $collection->max('y'); $y++) {
        $id = $collection->closestId($x, $y);
        $counts[$id]++;
        if($collection->isTotalDistanceBelow($x, $y)) {
            $withinDistanceLimits++;
        }
        if (
            !isset($edgeIds[$id]) && (
                $x === $collection->min('x') ||
                $x === $collection->max('x') ||
                $y === $collection->min('y') ||
                $y === $collection->max('y'))
        ) {
            $edgeIds[$id] = true;
        }
    }
}

// Everything touching an edge will be infinite, so should be removed
foreach (array_keys($edgeIds) as $id) {
    unset($counts[$id]);
};

rsort($counts);

echo "Part 1: " . $counts[0].PHP_EOL;
echo "Part 2: " . $withinDistanceLimits;