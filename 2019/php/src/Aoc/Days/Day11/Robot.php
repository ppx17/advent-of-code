<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day11;

use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;
use Ppx17\Aoc2019\Aoc\Days\Day9\IntCode;

class Robot
{
    private const WHITE = '#';
    private const BLACK = '.';

    private IntCode $computer;
    private Vector $location;
    private Direction $direction;
    public Map $map;
    private bool $outState = true;

    public function __construct(array $computerCode)
    {
        $this->computer = new IntCode($computerCode);
        $this->map = new Map();
    }

    public function run(int $initialTile)
    {
        $this->location = new Vector(0, 0);
        $this->direction = Direction::up();

        $this->computer->inputList[] = $initialTile;
        $this->computer->outputCallable = function ($out) {
            ($this->outState) ? $this->processColor($out) : $this->processTurn($out);
            $this->outState = !$this->outState;
        };
        $this->computer->run();
    }

    private function processColor(int $color)
    {
        $this->map->paint($this->location, $color === 1 ? self::WHITE : self::BLACK);
    }

    private function processTurn(int $direction)
    {
        $this->direction = ($direction === 1) ? $this->direction->right() : $this->direction->left();
        $this->location = $this->location->add($this->direction);
        $this->computer->inputList[] = $this->map->color($this->location) === self::BLACK ? 0 : 1;
    }
}
