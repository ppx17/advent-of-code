<?php


namespace Ppx17\Aoc2015\Aoc\Runner\Validator;


use Ppx17\Aoc2015\Aoc\Runner\Result;

class ValidatedResult
{
    /**
     * @var Result
     */
    private Result $result;
    private Part $part1;
    private Part $part2;

    public function __construct(Result $result)
    {
        $this->part1 = new Part(1);
        $this->part2 = new Part(2);
        $this->setResult($result);
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    public function setResult(Result $result): void
    {
        $this->result = $result;
        $this->part1->setResult($result->getPart1());
        $this->part2->setResult($result->getPart2());
    }

    public function getPart1(): Part
    {
        return $this->part1;
    }

    public function getPart2(): Part
    {
        return $this->part2;
    }
}