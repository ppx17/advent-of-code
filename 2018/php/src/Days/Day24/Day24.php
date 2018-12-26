<?php

namespace Ppx17\Aoc2018\Days\Day24;

use Ppx17\Aoc2018\Days\Day;

class Day24 extends Day
{
    public function part1(): string
    {
        return (new SimulatorFactory())->create($this->data)->fightToDeath();
    }

    public function part2(): string
    {
        $factor = 1;
        for($boost = $factor; $boost < 100; $boost++) {
            $simulator = (new SimulatorFactory())->create($this->data, $boost);
            $result = $simulator->fightToDeath();
            if($simulator->immuneCount() > 0 && $simulator->infectionCount() === 0) {
                return (string)$result;
            }
        }
    }
}