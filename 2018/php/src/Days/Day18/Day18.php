<?php
namespace Ppx17\Aoc2018\Days\Day18;

use Ppx17\Aoc2018\Days\Day;

class Day18 extends Day
{

    private $simulator1;
    private $simulator2;

    public function __construct(string $data)
    {
        parent::__construct($data);
        $this->simulator1 = new Simulator($data);
        $this->simulator2 = clone $this->simulator1;
    }

    public function part1(): string
    {
        return (string)$this->simulator1->simulate(10);
    }

    public function part2(): string
    {
        return (string)$this->simulator2->simulate(1000000000);
    }
}