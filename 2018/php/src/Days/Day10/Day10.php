<?php

namespace Ppx17\Aoc2018\Days\Day10;


use Ppx17\Aoc2018\Days\Day;

class Day10 extends Day
{
    private $sky;

    public function __construct(string $data)
    {
        parent::__construct($data);
        preg_match_all(
            '/position=<\s*(?<px>-?\d+),\s*(?<py>-?\d+)> velocity=<\s*(?<vx>-?\d+),\s*(?<vy>-?\d+)>/',
            $data,
            $matches,
            PREG_SET_ORDER
        );

        $this->sky = new Sky();

        foreach ($matches as $match) {
            $this->sky->addLight($match['px'], $match['py'], $match['vx'], $match['vy']);
        }

        $this->solve();
    }

    public function part1(): string
    {
        return $this->sky->print();
    }

    public function part2(): string
    {
        return $this->sky->getStepsTaken();
    }

    private function solve()
    {
        $size = $this->sky->height();
        $jumpSize = 1000;
        do {
            $this->sky->move($jumpSize);
            $newSize = $this->sky->height();
            if ($size < $newSize && $jumpSize > 1) {
                // we've jumped to far, revert and go to next size
                $this->sky->move(-$jumpSize * 2);
                $newSize = $this->sky->height();
                $jumpSize /= 10;
            }
            $size = $newSize;
        } while ($this->sky->height() > 10);
    }
}