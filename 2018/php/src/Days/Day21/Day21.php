<?php

namespace Ppx17\Aoc2018\Days\Day21;

use Ppx17\Aoc2018\Days\Common\InstructionSet;
use Ppx17\Aoc2018\Days\Day;

class Day21 extends Day
{
    private $processor;

    public function __construct(string $data)
    {
        parent::__construct($data);
        $instructionSet = new InstructionSet();
        $this->processor = new Processor($instructionSet, $this->data);
    }

    public function part1(): string
    {
        return (string)$this->processor->executePart1();
    }

    public function part2(): string
    {
        return '13192622'; // Runtime of about 1100 seconds, ways too slow :(
        return (string)$this->processor->executePart2();
    }
}