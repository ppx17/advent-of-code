<?php


namespace Ppx17\Aoc2015\Aoc\Runner;


interface DayInterface
{
    public function dayNumber(): int;

    public function setInput(string $input): void;
    public function getInput(): string;

    public function setUp(): void;

    public function part1(): string;
    public function part2(): string;
}