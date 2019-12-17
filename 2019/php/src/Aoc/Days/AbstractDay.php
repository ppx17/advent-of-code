<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Runner\DayInterface;

abstract class AbstractDay implements DayInterface
{
    private string $input = '';

    public function setUp(): void
    {

    }

    public function getInput(): string
    {
        return $this->input;
    }

    public function getInputTrimmed(): string
    {
        return trim($this->getInput());
    }

    public function getInputCsv(): array
    {
        return explode(',', $this->getInputTrimmed());
    }

    public function getInputIntCode(): array
    {
        return array_map('intval', $this->getInputCsv());
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
