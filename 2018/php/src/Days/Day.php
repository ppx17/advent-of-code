<?php

namespace Ppx17\Aoc2018\Days;

use Ppx17\Aoc2018\Runnable;

abstract class Day implements Runnable
{
    protected $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function dataLines(): array {
        return explode("\n", $this->data);
    }

    abstract public function part1(): string;

    abstract public function part2(): string;
}