<?php

namespace Ppx17\Aoc2019\Aoc\Days;

use Ppx17\Aoc2019\Aoc\Days\Day9\IntCode;

class Day9 extends AbstractDay
{
    private IntCode $intCode;

    public function dayNumber(): int
    {
        return 9;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->intCode = new IntCode(array_map('intval', explode(',', $this->getInput())));
    }

    public function part1(): string
    {
        $this->intCode->inputList = [1];
        $this->intCode->run();
        return $this->intCode->output;
    }

    public function part2(): string
    {
        $this->intCode->reset();
        $this->intCode->inputList = [2];
        $this->intCode->run();
        return $this->intCode->output;
    }
}
