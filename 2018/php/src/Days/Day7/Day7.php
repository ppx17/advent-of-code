<?php

namespace Ppx17\Aoc2018\Days\Day7;


use Ppx17\Aoc2018\Days\Day;

class Day7 extends Day
{
    private $solver;

    public function __construct(string $data)
    {
        parent::__construct($data);

        preg_match_all(
            "/Step (?<requirement>[A-Z]) must be finished before step (?<target>[A-Z]) can begin./",
            $data,
            $rules,
            PREG_SET_ORDER);

        $steps = new Steps();

        foreach ($rules as $rule) {
            $steps->addRule($rule['target'], $rule['requirement']);
        }

        $this->solver = new Solver($steps);
    }

    public function part1(): string
    {
        return $this->solver->getOrder();
    }

    public function part2(): string
    {
        return $this->solver->getDuration(5);
    }
}