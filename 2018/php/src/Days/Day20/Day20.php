<?php

namespace Ppx17\Aoc2018\Days\Day20;

use Ppx17\Aoc2018\Days\Day;

class Day20 extends Day
{
    private $distances;

    public function __construct(string $data)
    {
        parent::__construct($data);

        $parser = new Parser($data);
        $parser->parse();
        $this->distances = $parser->getNodes()->distances($parser->getInitialNode());
    }

    public function part1(): string
    {
        return (string)max($this->distances);
    }

    public function part2(): string
    {
        return (string)count(array_filter($this->distances, function ($d) {
            return $d >= 1000;
        }));
    }
}