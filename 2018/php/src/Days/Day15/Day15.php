<?php

namespace Ppx17\Aoc2018\Days\Day15;


use Ppx17\Aoc2018\Days\Day;

class Day15 extends Day
{
    public function part1(): string
    {
        return (new Simulator(new Map($this->data)))
            ->simulate(false);
    }

    public function part2(): string
    {
        for($elfStrength = 4; $elfStrength < 30; $elfStrength++) {
            try {
                return (new Simulator(new Map($this->data, $elfStrength), true))
                    ->simulate(false);
            }catch(ElfDiedException $ex) {
                continue;
            }
        }
        return 'Elves died everywhere :(';
    }
}
