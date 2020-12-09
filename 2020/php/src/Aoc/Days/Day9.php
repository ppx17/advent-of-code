<?php


namespace Ppx17\Aoc2020\Aoc\Days;


class Day9 extends AbstractDay
{
    private const PREAMBLE = 25;
    private int $nc;
    private array $numbers;
    private int $weakness = 0;

    public function dayNumber(): int
    {
        return 9;
    }

    public function setUp(): void
    {
        $this->numbers = array_map(fn($x) => (int)$x, $this->getInputLines());
        $this->nc = count($this->numbers);
    }

    public function part1(): string
    {
        for ($i = self::PREAMBLE; $i < $this->nc; $i++) {
            $current = $this->numbers[$i];
            if (!$this->hasSum($i, $current)) {
                $this->weakness = $current;
                return $current;
            }
        }
        return 'not found';
    }

    private function hasSum(int $index, int $sum): bool
    {
        for ($a = $index - 1; $a >= $index - self::PREAMBLE - 1; $a--) {
            for ($b = $a - 1; $b >= $index - self::PREAMBLE; $b--) {
                if ($this->numbers[$a] + $this->numbers[$b] === $sum) {
                    return true;
                }
            }
        }
        return false;
    }

    public function part2(): string
    {
        $list = [];
        for ($i = 0; $i < $this->nc; $i++) {
            $list[] = $this->numbers[$i];

            if (count($list) === 1) continue;

            while (array_sum($list) > $this->weakness && count($list) > 2) {
                array_shift($list);
            }

            if (array_sum($list) === $this->weakness) {
                return min($list) + max($list);
            }
        }
        return 'not found';
    }
}
