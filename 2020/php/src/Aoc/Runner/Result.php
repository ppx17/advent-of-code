<?php


namespace Ppx17\Aoc2020\Aoc\Runner;


class Result
{
    /**
     * @var DayInterface
     */
    private DayInterface $day;

    private string $part1;
    private string $part2;

    private float $timeSetup;
    private float $timePart1;
    private float $timePart2;

    public function __construct(DayInterface $day)
    {
        $this->day = $day;
    }

    public function getPart1(): string
    {
        return $this->part1;
    }

    public function setPart1(string $part1): void
    {
        $this->part1 = $part1;
    }

    public function getPart2(): string
    {
        return $this->part2;
    }

    public function setPart2(string $part2): void
    {
        $this->part2 = $part2;
    }

    public function getTimeSetup(): float
    {
        return $this->timeSetup;
    }

    public function setTimeSetup(float $timeSetup): void
    {
        $this->timeSetup = $timeSetup;
    }

    public function getTimePart1(): float
    {
        return $this->timePart1;
    }

    public function setTimePart1(float $timePart1): void
    {
        $this->timePart1 = $timePart1;
    }

    public function getTimePart2(): float
    {
        return $this->timePart2;
    }

    public function setTimePart2(float $timePart2): void
    {
        $this->timePart2 = $timePart2;
    }

    public function getTimeTotal(): float
    {
        return $this->getTimeSetup() + $this->getTimePart1() + $this->getTimePart2();
    }

    public function getDay(): DayInterface
    {
        return $this->day;
    }

}