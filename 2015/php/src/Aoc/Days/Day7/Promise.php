<?php


namespace Ppx17\Aoc2015\Aoc\Days\Day7;


abstract class Promise
{
    public array $inputVariables;
    abstract public function resolve(array $inputs): int;
}