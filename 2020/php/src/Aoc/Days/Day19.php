<?php

namespace Ppx17\Aoc2020\Aoc\Days;

use Illuminate\Support\Collection;

class Day19 extends AbstractDay
{
    private Collection $rules;
    private Collection $messages;
    private Collection $chars;

    public function dayNumber(): int
    {
        return 19;
    }

    public function setUp(): void
    {
        $inputParts = explode("\n\n", $this->getInputTrimmed());
        $this->rules = collect(explode("\n", $inputParts[0]))
            ->map(fn($x) => explode(': ', $x))
            ->mapWithKeys(fn($x) => [(int)$x[0] => $x[1]]);
        $this->messages = collect(explode("\n", $inputParts[1]));
        $this->chars = $this->rules->filter(fn($r) => $r[0] === '"')
            ->map(fn($c) => substr($c, 1, 1));
    }

    public function part1(): string
    {
        $regex = $this->resolveRule(0);

        return $this->messages
            ->filter(fn($m) => preg_match("#^{$regex}$#", $m) === 1)
            ->count();
    }

    public function part2(): string
    {
        $fortyTwo = $this->resolveRule(42);
        $eight = $fortyTwo . '+';
        $thirtyOne = $this->resolveRule(31);

        $matches = new Collection();

        for ($i = 1; $i < 10; $i++) { // found 5 to be the min.
            $regex = "#^{$eight}{$fortyTwo}{{$i}}{$thirtyOne}{{$i}}$#";
            $matches = $matches->concat($this->messages->filter(fn($m) => preg_match($regex, $m) === 1));
        }

        return $matches->count();
    }

    private function resolveRule(int $rule): string
    {
        if ($this->chars->has($rule)) {
            return $this->chars->get($rule);
        }

        $hasOr = false;
        $parts = collect(explode(' ', $this->rules->get($rule)))
            ->map(function ($p) use (&$hasOr) {
                if ($p === '|') {
                    $hasOr = true;
                    return '|';
                }
                return $this->resolveRule((int)$p);
            })
            ->join('');

        return $hasOr ? '(' . $parts . ')' : $parts;
    }
}
