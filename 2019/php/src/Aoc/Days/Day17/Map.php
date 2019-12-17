<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day17;

use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;

class Map extends \Ppx17\Aoc2019\Aoc\Days\Day11\Map
{
    public function each(callable $callback)
    {
        for ($x = $this->minX(); $x <= $this->maxX(); $x++) {
            for ($y = $this->minY(); $y <= $this->maxY(); $y++) {
                $pos = new Vector($x, $y);
                $callback($pos, $this->color($pos));
            }
        }
    }
}
