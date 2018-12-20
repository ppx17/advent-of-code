<?php

namespace Ppx17\Aoc2018;


interface Runnable
{
    public function __construct(string $data);

    public function part1(): string;

    public function part2(): string;
}