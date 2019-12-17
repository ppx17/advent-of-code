<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day15;

use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;
use Ppx17\Aoc2019\Aoc\Days\Day13\IntCode;

class Droid
{
    public const TILE_WALL = 0;
    public const TILE_SPACE = 1;
    public const TILE_OXYGEN = 2;

    public IntCode $computer;
    public Vector $location;
    public Direction $direction;
    private \Closure $tileDetected;
    public int $generation;

    public function __construct(array $code, int $generation = 0)
    {
        $this->computer = new IntCode($code);
        $this->computer->inputCallable = fn() => $this->direction->toInt();
        $this->computer->outputCallable = fn($out) => $this->outputReceived($out);
        $this->location = new Vector(0, 0);
        $this->direction = new Direction(1, 0);
        $this->generation = $generation;
    }

    public function onTileDetected(\Closure $tileDetected): void
    {
        $this->tileDetected = $tileDetected;
    }

    public function spawn(Direction $direction): Droid
    {
        $child = new Droid($this->computer->memory, $this->generation + 1);
        $child->location = $this->location;
        $child->direction = $direction;
        $child->tileDetected = $this->tileDetected;
        return $child;
    }

    private function outputReceived(int $out)
    {
        $position = $this->location->add($this->direction);
        if($out !== self::TILE_WALL) {
            $this->location = clone $position;
        }
        $this->tileDetected($out, $position);
    }

    private function tileDetected(int $tile, Vector $location)
    {
        if($this->tileDetected !== null) {
            ($this->tileDetected)($this, $tile, $location);
        }
    }

}
