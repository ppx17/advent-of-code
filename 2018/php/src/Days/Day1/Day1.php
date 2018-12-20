<?php

namespace Ppx17\Aoc2018\Days\Day1;


use Ppx17\Aoc2018\Days\Day;

class Day1 extends Day
{
    private $deltas;

    public function __construct(string $data)
    {
        parent::__construct($data);

        $this->deltas = array_map("intval", explode("\n", trim($data)));
    }

    public function part1(): string
    {
        return (string)array_sum($this->deltas);
    }

    public function part2(): string
    {
        $frequency = 0;
        $seen = [];

        while(true) {
            foreach($this->deltas as $statement) {
                $frequency += $statement;
                if(isset($seen[$frequency])) {
                    return (string)$frequency;
                }
                $seen[$frequency] = true;
            }
        }
    }
}