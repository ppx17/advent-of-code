<?php

namespace Ppx17\Aoc2018\Days\Day12;


use Ppx17\Aoc2018\Days\Day;

class Day12 extends Day
{
    private $rules;
    private $state;
    private $currentGeneration = 0;

    public function __construct(string $data)
    {
        parent::__construct($data);

        $lines = $this->dataLines();
        $this->state = str_split((explode(": ", $lines[0]))[1], 1);

        preg_match_all(
            "/(?<pattern>[#\.]+) => (?<replace>[#\.])/",
            $data,
            $matches,
            PREG_SET_ORDER);

        $this->rules = array_combine(
            array_column($matches, 'pattern'),
            array_column($matches, 'replace')
        );
    }

    public function part1(): string
    {
        for ($generation = 1; $generation <= 20; $generation++) {
            $this->runGeneration();
        }

        return $this->sumOfPotNumbers();
    }

    public function part2(): string
    {
        $initial = 0;
        $increment = 0;

        // TODO: Automatic pattern recognition
        $skipFirst = 100;
        $repetitiveIn = 10;

        while($this->currentGeneration <= ($skipFirst + $repetitiveIn))
        {
           $this->runGeneration();
            if ($this->currentGeneration === $skipFirst) {
                $initial = $this->sumOfPotNumbers();
            } elseif ($this->currentGeneration === ($skipFirst + $repetitiveIn)) {
                $increment = $this->sumOfPotNumbers() - $initial;
            }
        }

        $rounds = (50000000000 / $repetitiveIn) - $repetitiveIn;

        return (string)($initial + ($rounds * $increment));
    }

    private function sumOfPotNumbers(): int
    {
        $sum = 0;
        foreach ($this->state as $potNumber => $plant) {
            if ($plant === '#') {
                $sum += $potNumber;
            }
        }
        return $sum;
    }

    private function runGeneration(): void
    {
        $this->currentGeneration++;
        $newState = [];
        $min = array_search('#', $this->state) - 2;
        $max = array_search('#', array_reverse($this->state, true)) + 2;
        for ($pot = $min; $pot <= $max; $pot++) {
            $search = ($this->state[$pot - 2] ?? '.') .
                ($this->state[$pot - 1] ?? '.') .
                ($this->state[$pot] ?? '.') .
                ($this->state[$pot + 1] ?? '.') .
                ($this->state[$pot + 2] ?? '.');
            $newState[$pot] = $this->rules[$search];
        }
        $this->state = $newState;
    }
}