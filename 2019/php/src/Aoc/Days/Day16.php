<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Illuminate\Support\Collection;

class Day16 extends AbstractDay
{
    private Collection $pattern;
    private Collection $repeatedPatterns;

    public function dayNumber(): int
    {
        return 16;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->repeatedPatterns = collect();
        $this->pattern = collect([0, 1, 0, -1]);
        // Might want to optimize memory usage. ':D
        ini_set('memory_limit', '1024M');
    }

    public function part1(): string
    {
        $input = collect(str_split($this->getInputTrimmed()))->map(fn($x) => intval($x));

        return $this->phaseTimes($input, 100)
            ->slice(0, 8)
            ->join('');
    }

    public function part2(): string
    {
        $inputString = str_repeat($this->getInputTrimmed(), 10000);
        $offset = intval(substr($this->getInputTrimmed(), 0, 7));

        if ($offset < strlen($inputString) / 2) {
            throw new \RuntimeException('Offset too small for shortcut');
        }

        $endPart = collect(str_split($inputString))
            ->slice($offset)
            ->map(fn($x) => intval($x))
            ->values();

        return $this->phaseShortcut($endPart)
            ->slice(0, 8)
            ->join('');
    }

    private function phase(array $input): array
    {
        $result = [];
        foreach ($input as $rowNum => $row) {
            $pattern = $this->repeatPattern($rowNum + 1);
            $patternLength = ($rowNum + 1) * 4;
            $rowSum = 0;
            foreach ($input as $key => $col) {
                $rowSum += ($col * $pattern[($key + 1) % $patternLength]);
            }
            $result[] = abs($rowSum) % 10;
        }
        return $result;
    }

    private function phaseTimes(Collection $input, int $times): Collection
    {
        $input = $input->toArray();
        for ($i = 0; $i < $times; $i++) {
            $input = $this->phase($input);
        }
        return collect($input);
    }

    private function phaseShortcut(Collection $endPart): Collection
    {
        $endPart = $endPart->toArray();
        $len = count($endPart);
        for ($phase = 0; $phase < 100; $phase++) {
            for ($i = $len - 2; $i >= 0; $i--) {
                $endPart[$i] = ($endPart[$i] + $endPart[$i + 1]) % 10;
            }
        }
        return collect($endPart);
    }

    private function repeatPattern(int $times)
    {
        if (!$this->repeatedPatterns->has($times)) {
            $result = Collect();
            $this->pattern->each(fn($x) => Collection::times($times)->each(fn() => $result->push($x)));
            $this->repeatedPatterns->put($times, $result);
        }
        return $this->repeatedPatterns->get($times);
    }
}
