<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Ppx17\Aoc2020\Aoc\Runner\DayInterface;

abstract class AbstractDay implements DayInterface
{
    private string $input = '';

    public function setUp(): void
    {

    }

    public function getInput(bool $trim = true): string
    {
        return $trim ? trim($this->input) : $this->input;
    }

    public function setInput(string $input): void
    {
        $this->input = $input;
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

    public function getInputLines(bool $trim = true): array
    {
        return ($trim)
            ? explode("\n", trim($this->getInput()))
            : explode("\n", $this->getInput());
    }
}
