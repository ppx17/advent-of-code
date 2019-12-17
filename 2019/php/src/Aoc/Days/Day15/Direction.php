<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day15;

class Direction extends \Ppx17\Aoc2019\Aoc\Days\Day11\Direction
{
    public function toInt(): int
    {
        if(abs($this->x) + abs($this->y) > 1)
        {
            throw new \RuntimeException('Invalid direction');
        }
        if($this->y === -1) {
            return 1; // north
        }elseif($this->y === 1) {
            return 2; // south
        }elseif($this->x === -1) {
            return 3; //west
        }elseif($this->x === 1) {
            return 4;
        }else{
            throw new \RuntimeException('Standing still');
        }
    }

    public function reverse(): self
    {
        return new static(-$this->x, -$this->y);
    }
}
