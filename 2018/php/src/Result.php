<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 12:26
 */

namespace Ppx17\Aoc2018;


class Result
{
    private $name;
    private $status;
    private $totalRuntimeMs;
    private $totalMemoryUsedBytes;

    private $part1;
    private $part2;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function setStatusCorrect(): self
    {
        return $this->setStatus('✔ correct answer');
    }

    public function setStatusWrong(): self
    {
        return $this->setStatus('⨯ wrong answer');
    }

    public function setStatusUnknown(): self
    {
        return $this->setStatus('? Solution unknown');
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTotalRuntimeMs(): float
    {
        return $this->totalRuntimeMs ?? 0.0;
    }

    public function setTotalRuntimeMs($totalRuntimeMs): self
    {
        $this->totalRuntimeMs = $totalRuntimeMs;
        return $this;
    }

    public function getTotalMemoryUsedBytes(): int
    {
        return $this->totalMemoryUsedBytes ?? 0;
    }

    public function setTotalMemoryUsedBytes(int $bytes): self
    {
        $this->totalMemoryUsedBytes = $bytes;
        return $this;
    }

    public function setPart1(string $part1)
    {
        $this->part1 = $part1;
        return $this;
    }

    public function setPart2(string $part2)
    {
        $this->part2 = $part2;
        return $this;
    }

    public function getPart1()
    {
        return $this->part1;
    }

    public function getPart2()
    {
        return $this->part2;
    }
}