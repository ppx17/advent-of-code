<?php

namespace Ppx17\Aoc2018\Days\Day14;


use Ppx17\Aoc2018\Days\Day;

class Day14 extends Day
{
    private $searcher;
    private $part2 = '';

    public function __construct(string $data)
    {
        parent::__construct($data);

        $this->searcher = new ScoreSearcher($this->data);
        $this->part2 = $this->searcher->search();

    }

    public function part1(): string
    {
        return $this->searcher->getPart1();
    }

    public function part2(): string
    {
        return $this->part2;
    }
}