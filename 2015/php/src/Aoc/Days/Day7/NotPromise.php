<?php


namespace Ppx17\Aoc2015\Aoc\Days\Day7;


class NotPromise extends Promise
{
    public function resolve(array $inputs): int
    {
        return ~ $inputs[0];
    }
}