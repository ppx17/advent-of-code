<?php
namespace Ppx17\Aoc2018\Days\Day17;

use Ppx17\Aoc2018\Days\Common\Vector;
use Ppx17\Aoc2018\Days\Day;

class Day17 extends Day
{
    protected $map;
    protected $simulator;

    public function __construct(string $data)
    {
        parent::__construct($data);

        $this->loadMap();

        $this->loadSimulator();

        $this->simulator->simulate();
    }

    public function part1(): string
    {
        return (string)$this->map->countWater();
    }

    public function part2(): string
    {
        return (string)$this->map->countStillWater();
    }

    private function loadMap()
    {
        preg_match_all('#x=(?<x>\d+), y=(?<y1>\d+)..(?<y2>\d+)#m',
            $this->data, $vertical_veins, PREG_SET_ORDER
        );
        preg_match_all('#y=(?<y>\d+), x=(?<x1>\d+)..(?<x2>\d+)#m',
            $this->data, $horizontal_veins, PREG_SET_ORDER
        );

        $this->map = new Map();
        foreach ($vertical_veins as $vein) {
            $this->map->addVein(new Vector($vein['x'], $vein['y1']), new Vector($vein['x'], $vein['y2']));
        }
        foreach ($horizontal_veins as $vein) {
            $this->map->addVein(new Vector($vein['x1'], $vein['y']), new Vector($vein['x2'], $vein['y']));
        }
    }

    private function loadSimulator()
    {
        $start = new Vector(500, 1);

        $this->simulator = new Simulator($this->map, $start);
    }
}