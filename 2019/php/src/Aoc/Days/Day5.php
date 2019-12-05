<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Ppx17\Aoc2019\Aoc\Days\Day5\IntCode;

class Day5 extends AbstractDay
{
    private IntCode $intCode;

    public function dayNumber(): int
    {
        return 5;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->intCode = new IntCode(array_map('intval', explode(',', $this->getInput())));
    }

    public function part1(): string
    {
        $this->intCode->input = 1;
        $this->intCode->run();
        return (string)$this->intCode->output;
    }

    public function part2(): string
    {
        $this->intCode->reset();
        $this->intCode->input = 5;
        $this->intCode->run();
        return (string)$this->intCode->output;
    }
}
