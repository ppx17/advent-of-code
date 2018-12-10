<?php
$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");

class Sky
{
    private $positionsX = [];
    private $positionsY = [];
    private $velocitiesX = [];
    private $velocitiesY = [];
    private $lights = 0;

    public function addLight(int $posX, int $posY, int $velX, int $velY): void
    {
        $this->positionsX[] = $posX;
        $this->positionsY[] = $posY;
        $this->velocitiesX[] = $velX;
        $this->velocitiesY[] = $velY;
        $this->lights++;
    }

    public function move(): void
    {
        for ($i = 0; $i < $this->lights; $i++) {
            $this->positionsX[$i] += $this->velocitiesX[$i];
            $this->positionsY[$i] += $this->velocitiesY[$i];
        }
    }

    public function height(): int
    {
        return max($this->positionsY) - min($this->positionsY);
    }

    public function print(): string
    {
        $sky = [];
        for ($y = min($this->positionsY); $y <= max($this->positionsY); $y++) {
            for ($x = min($this->positionsX); $x <= max($this->positionsX); $x++) {
                $sky[$y][$x] = ".";
            }
        }
        for($i=0;$i<$this->lights;$i++) {
            $sky[$this->positionsY[$i]][$this->positionsX[$i]] = '#';
        }

        return implode(PHP_EOL, array_map(function ($row) {
                return implode("", $row);
            }, $sky)) . PHP_EOL;
    }
}


preg_match_all(
    '/position=<\s*(?<px>-?\d+),\s*(?<py>-?\d+)> velocity=<\s*(?<vx>-?\d+),\s*(?<vy>-?\d+)>/',
    $data,
    $matches, PREG_SET_ORDER);

;
$sky = new Sky();

foreach ($matches as $match) {
    $sky->addLight($match['px'], $match['py'], $match['vx'], $match['vy']);
}

$iterations = 0;
do {
    $sky->move();
    $iterations++;
} while ($sky->height() > 10);


echo "Part 1:" . PHP_EOL;
echo $sky->print();
echo "Part 2: " . $iterations.PHP_EOL;