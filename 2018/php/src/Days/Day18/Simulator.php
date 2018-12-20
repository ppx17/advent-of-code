<?php
namespace Ppx17\Aoc2018\Days\Day18;

class Simulator
{
    private $grid = [];
    private $height;
    private $width;

    public function __construct(string $data)
    {
        $this->grid = [];
        $grid = explode("\n", $data);
        foreach ($grid as $y => $row) {
            $this->grid[$y] = str_split($row, 1);
        }

        $this->height = count($this->grid);
        $this->width = count($this->grid[0]);
    }

    public function simulate(int $minutes): int
    {
        if ($minutes < 5000) {
            return $this->simulateAll($minutes);
        } else {
            return $this->simulateFindPattern($minutes);
        }
    }

    public function __toString(): string
    {
        return implode("\n", array_map(function ($row) {
                return implode("", $row);
            }, $this->grid)) . PHP_EOL;
    }

    public function resourceValue(): int
    {
        $trees = 0;
        $camps = 0;

        foreach ($this->grid as $row) {
            foreach ($row as $acre) {
                if ($acre === '|') {
                    $trees++;
                }
                if ($acre === '#') {
                    $camps++;
                }
            }
        }
        return $trees * $camps;
    }

    private function simulateMinute(): void
    {
        $newGrid = [];

        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {

                $counts = $this->countNextTo($x, $y);

                if ($this->get($x, $y) === '.' && $counts['|'] >= 3) {
                    $newGrid[$y][$x] = '|';
                } elseif ($this->get($x, $y) === '|' && $counts['#'] >= 3) {
                    $newGrid[$y][$x] = '#';
                } elseif ($this->get($x, $y) === '#' && ($counts['#'] < 1 || $counts['|'] < 1)) {
                    $newGrid[$y][$x] = '.';
                } else {
                    $newGrid[$y][$x] = $this->get($x, $y);
                }
            }
        }

        $this->grid = $newGrid;
    }

    private function countNextTo(int $x, int $y): array
    {
        $result = [];
        // Above
        $result[$this->get($x - 1, $y - 1)]++;
        $result[$this->get($x, $y - 1)]++;
        $result[$this->get($x + 1, $y - 1)]++;

        //Beside
        $result[$this->get($x + 1, $y)]++;
        $result[$this->get($x - 1, $y)]++;

        //Below
        $result[$this->get($x - 1, $y + 1)]++;
        $result[$this->get($x, $y + 1)]++;
        $result[$this->get($x + 1, $y + 1)]++;
        return $result;
    }

    private function get(int $x, int $y)
    {
        return $this->grid[$y][$x] ?? 'O';
    }

    private function simulateAll(int $minutes): int
    {
        for ($minute = 1; $minute <= $minutes; $minute++) {
            $this->simulateMinute();
        }
        return $this->resourceValue();
    }

    private function simulateFindPattern(int $minutes): int
    {
        $history = [];
        $seenCount = [];
        $minute = 1;
        do {
            $this->simulateMinute();
            $value = $this->resourceValue();
            $history[$minute] = $value;
            $seenCount[$value]++;

            $minute++;
        } while (max($seenCount) < 5);

        arsort($seenCount, SORT_NUMERIC);
        $mostSeenValue = key($seenCount);

        $mostSeenAtMinutes = [];
        foreach ($history as $minute => $value) {
            if ($value === $mostSeenValue) {
                $mostSeenAtMinutes[] = $minute;
            }
        }

        $increment = $mostSeenAtMinutes[1] - $mostSeenAtMinutes[0];
        $firstOccurence = $mostSeenAtMinutes[0];

        return $history[$firstOccurence + (($minutes - $firstOccurence) % $increment)];
    }
}