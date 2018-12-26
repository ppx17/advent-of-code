<?php

namespace Ppx17\Aoc2018\Days\Day25;

use Ppx17\Aoc2018\Days\Day;

class Day25 extends Day
{

    public function part1(): string
    {
        $constellations = (new ConstellationFactory())->create($this->dataLines());

        return count($constellations);
    }

    public function part2(): string
    {
        return 'Finished!';
    }
}