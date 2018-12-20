<?php

namespace Ppx17\Aoc2018\Days\Day19;

use Ppx17\Aoc2018\Days\Common\InstructionSet;
use Ppx17\Aoc2018\Days\Day;

class Day19 extends Day
{
    private $cpu1;
    private $cpu2;

    public function __construct(string $data)
    {
        parent::__construct($data);

        $this->cpu1 = new Processor(new InstructionSet(), $data);
        $this->cpu2 = clone $this->cpu1;
    }

    public function part1(): string
    {
        return (string)$this->cpu1->execute();
    }

    public function part2(): string
    {
        $this->cpu2->register(0, 1);
        return (string)$this->cpu2->execute();
    }
}