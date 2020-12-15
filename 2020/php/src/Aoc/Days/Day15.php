<?php


namespace Ppx17\Aoc2020\Aoc\Days;

class Day15 extends AbstractDay
{
    private array $numbers;
    private array $diffs;
    private array $previous;

    public function dayNumber(): int
    {
        return 15;
    }

    public function setUp(): void
    {
        ini_set('memory_limit', '384M');
        $this->numbers = array_map(fn($x) => (int)$x, explode(',', $this->getInputTrimmed()));
    }

    public function part1(): string
    {
        return $this->play(2020);
    }

    private function play($rounds): int
    {
        $this->diffs = [];
        $this->previous = [];
        $lastSpoken = 0;

        foreach ($this->numbers as $idx => $number) {
            $lastSpoken = $this->speak($number, $idx + 1);
        }

        for ($round = count($this->numbers) + 1; $round <= $rounds; $round++) {
            $lastSpoken = $this->speak($this->diffs[$lastSpoken] ?? 0, $round);
        }
        return $lastSpoken;
    }

    private function speak(int $number, int $round): int
    {
        if(isset($this->previous[$number])) {
            $this->diffs[$number] = $round - $this->previous[$number] ?? 0;
        }
        $this->previous[$number] = $round;

        return $number;
    }

    public function part2(): string
    {
        return $this->play(30_000_000);
    }
}
