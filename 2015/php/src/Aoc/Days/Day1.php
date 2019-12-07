<?php


namespace Ppx17\Aoc2015\Aoc\Days;


class Day1 extends AbstractDay
{
    public function dayNumber(): int
    {
        return 1;
    }

    public function part1(): string
    {
        return substr_count($this->getInput(), '(') - substr_count($this->getInput(), ')');
    }

    public function part2(): string
    {
        $level = 0;
        for($i = 0; $i < strlen($this->getInput()); $i++) {
            $level += $this->getInput()[$i] === '(' ? 1 : -1;
            if($level === -1) {
                return $i + 1;
            }
        }
        return 'unknown';
    }
}
