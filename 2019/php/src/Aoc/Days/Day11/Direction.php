<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day11;

use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;

class Direction extends Vector
{
    public static function up()
    {
        return new static(0, -1);
    }

    public function left()
    {
        if($this->x !== 0) {
            return new static(0, -$this->x);
        }elseif($this->y !== 0) {
            return new static($this->y, 0);
        }
    }

    public function right()
    {
        if($this->x !== 0) {
            return new static(0, $this->x);
        }elseif($this->y !== 0) {
            return new static(-$this->y, 0);
        }
    }
}
