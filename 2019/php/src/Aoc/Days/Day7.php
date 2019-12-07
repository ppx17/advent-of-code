<?php

namespace Ppx17\Aoc2019\Aoc\Days;

use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Days\Day7\IntCode;

class Day7 extends AbstractDay
{
    private IntCode $intCode;
    private Collection $combinations;

    public function dayNumber(): int
    {
        return 7;
    }

    public function setUp(): void
    {
        $this->intCode = new IntCode(array_map('intval', explode(',', $this->getInput())));
        $this->combinations = collect($this->phaseSettings());
    }

    public function part1(): string
    {
        return $this
            ->combinations
            ->map(fn(array $phase) => $this->runPhase($phase))
            ->max();
    }

    public function part2(): string
    {
        return
            $this
                ->combinations
                ->map(fn($x) => array_map(fn($y) => $y + 5, $x))
                ->map(fn(array $phase) => $this->runPhaseRecursive($phase))
                ->max();
    }

    private function phaseSettings(): \Generator
    {
        for ($a = 0; $a <= 4; $a++) {
            for ($b = 0; $b <= 4; $b++) {
                if ($b == $a) {
                    continue;
                }
                for ($c = 0; $c <= 4; $c++) {
                    if ($c == $a || $c == $b) {
                        continue;
                    }
                    for ($d = 0; $d <= 4; $d++) {
                        if ($d == $a || $d == $b || $d == $c) {
                            continue;
                        }
                        for ($e = 0; $e <= 4; $e++) {
                            if ($e == $a || $e == $b || $e == $c || $e == $d) {
                                continue;
                            }
                            yield [$a, $b, $c, $d, $e];
                        }
                    }
                }
            }
        }
    }

    private function runPhase(array $phases): int
    {
        $inputSignal = 0;
        foreach ($phases as $phase) {
            $this->intCode->reset();
            $this->intCode->inputList = [$phase, $inputSignal];
            $this->intCode->run();
            $inputSignal = $this->intCode->output;
        }
        return $inputSignal;
    }

    private function runPhaseRecursive(array $phase)
    {
        $this->intCode->reset();
        $computers = collect($phase)
            ->map(function ($p) {
                $clone = clone $this->intCode;
                $clone->inputList = [$p];
                return $clone;
            })
            ->tap(function (Collection $computers) {
                $computers->each(function (IntCode $computer, $index) use ($computers) {
                    $index += 1;
                    if ($index === $computers->count()) {
                        $index = 0;
                    }
                    $computer->outputCallable = function ($output) use ($computers, $index) {
                        $computers[$index]->inputList[] = $output;
                        $computers[$index]->run();
                    };
                });
            });

        $computers[0]->inputList[] = 0;
        $computers[0]->run();

        return $computers[4]->output;
    }
}
