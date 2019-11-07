<?php


namespace Ppx17\Aoc2019\Aoc\Runner;


interface DayInterface
{
    public function dayNumber(): int;

    public function part1(): string;
    public function part2(): string;
}