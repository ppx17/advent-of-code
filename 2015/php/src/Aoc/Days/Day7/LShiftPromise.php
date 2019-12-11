<?php


namespace Ppx17\Aoc2015\Aoc\Days\Day7;


class LShiftPromise extends Promise
{
    public int $distance;
    public function resolve(array $inputs): int
    {
        return $inputs[0] << $this->distance;
    }
}