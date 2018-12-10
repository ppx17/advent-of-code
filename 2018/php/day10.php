<?php
$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");

class Point
{
    public $x;
    public $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function add(Point $point)
    {
        $this->x += $point->x;
        $this->y += $point->y;
    }
}

class Light
{
    public $position;
    public $velocity;

    public function __construct(Point $position, Point $velocity)
    {
        $this->position = $position;
        $this->velocity = $velocity;
    }

    public function move()
    {
        $this->position->add($this->velocity);
    }
}

class Sky
{
    private $lights;

    public function __construct()
    {
        $this->lights = [];
    }

    public function addLight(Light $light): void
    {
        $this->lights[] = $light;
    }

    public function move(): void
    {
        foreach ($this->lights as $light) {
            $light->move();
        }
    }

    public function print()
    {
        $xs = $this->getXCoordinates();
        $ys = $this->getYCoordinates();
        $topLeft = new Point(min($xs), min($ys));
        $bottomRight = new Point(max($xs), max($ys));

        $sky = [];
        for ($y = $topLeft->y; $y <= $bottomRight->y; $y++) {
            for ($x = $topLeft->x; $x <= $bottomRight->x; $x++) {
                $sky[$y][$x] = ".";
            }
        }
        foreach ($this->lights as $light) {
            $sky[$light->position->y][$light->position->x] = '#';
        }

        return implode(PHP_EOL, array_map(function ($row) {
                return implode("", $row);
            }, $sky)) . PHP_EOL;
    }

    public function height(): int
    {
        $ys = $this->getYCoordinates();
        return max($ys) - min($ys);
    }

    private function getXCoordinates(): array
    {
        return array_map(function ($light) {
            return $light->position->x;
        }, $this->lights);
    }

    private function getYCoordinates(): array
    {
        return array_map(function ($light) {
            return $light->position->y;
        }, $this->lights);
    }
}


preg_match_all(
    '/position=<\s*(?<px>-?\d+),\s*(?<py>-?\d+)> velocity=<\s*(?<vx>-?\d+),\s*(?<vy>-?\d+)>/',
    $data,
    $matches, PREG_SET_ORDER);

$sky = new Sky();

foreach ($matches as $match) {
    $sky->addLight(
        new Light(
            new Point(intval($match['px']), intval($match['py'])),
            new Point(intval($match['vx']), intval($match['vy']))
        )
    );
}

$iterations = 0;
do {
    $sky->move();
    $iterations++;
} while ($sky->height() > 15);


echo "Part 1:" . PHP_EOL;
echo $sky->print();
echo "Part 2: " . $iterations;
