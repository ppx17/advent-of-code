<?php


namespace Ppx17\Aoc2015\Aoc\Days;


class Day4 extends AbstractDay
{
    private string $secret;

    public function dayNumber(): int
    {
        return 4;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->secret = trim($this->getInput());
    }

    public function part1(): string
    {
        return $this->hashWithPrefix('00000');
    }

    public function part2(): string
    {
        return $this->hashWithPrefix('000000');
    }

    private function hashWithPrefix(string $prefix)
    {
        for($i = 0; $i < 10_000_000; $i++) {
            if(substr(md5($this->secret . $i), 0, strlen($prefix)) === $prefix)
            {
                return (string)$i;
            }
        }
        return 'max attempts reached';
    }
}
