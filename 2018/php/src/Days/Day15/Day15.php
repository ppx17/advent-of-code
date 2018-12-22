<?php

namespace Ppx17\Aoc2018\Days\Day15;


use Ppx17\Aoc2018\Days\Day;

class Day15 extends Day
{
    public function part1(): string
    {
        return (new Simulator(new Map($this->data)))->simulate(false);
    }

    public function part2(): string
    {
        return '';
    }
}
// 57800 too high