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
    public int $generation;
    private int $discoveredTile;

    public function __construct(Vector $location, Direction $direction, array $code, int $generation = 0)
    {
        $this->computer = new IntCode($code);
        $this->computer->outputCallable = fn($out) => $this->outputReceived($out);
        $this->generation = $generation;
        $this->location = $location;
        $this->direction = $direction;

        $this->computer->inputList = [$this->direction->toInt()];
    }

    public function spawn(Direction $direction): Droid
    {
        return new Droid($this->location, $direction, $this->computer->memory, $this->generation + 1);
    }

    private function outputReceived(int $out)
    {
        $this->computer->halt();

        $position = $this->location->add($this->direction);
        if($out !== self::TILE_WALL) {
            $this->location = clone $position;
        }

        $this->discoveredTile = $out;
    }

    public function discover(): Tile
    {
        $this->computer->run();
        $tile = new Tile();
        $tile->type = $this->computer->output;
        if($tile->type === self::TILE_WALL) {
            $tile->location = $this->location->add($this->direction);
        }else{
            $tile->location = $this->location;
        }
        return $tile;
    }

}
