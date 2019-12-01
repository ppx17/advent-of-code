<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Ppx17\Aoc2019\Aoc\Runner\DayInterface;

abstract class AbstractDay implements DayInterface
{
    private $input = '';

    public function setUp(): void
    {

    }

    public function getInput(): string
    {
        return $this->input;
    }

    public function getInputLines(): array
    {
        return explode("\n", trim($this->getInput()));
    }

    public function setInput(string $input): void
    {
        $this->input = $input;
    }
}