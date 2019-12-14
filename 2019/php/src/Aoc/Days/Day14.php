<?php

namespace Ppx17\Aoc2019\Aoc\Days;

use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Days\Day14\Reaction;

class Day14 extends AbstractDay
{
    private Collection $reactions;
    private Collection $requirements;
    private int $requiredOre;
    private Collection $leftOvers;

    public function dayNumber(): int
    {
        return 14;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->reactions = collect($this->getInputLines())
            ->mapWithKeys(function ($line) {
                $halves = explode('=>', $line);
                $reaction = new Reaction();
                $result = explode(' ', trim($halves[1]));
                $reaction->result = $result[1];
                $reaction->amount = (int)$result[0];

                $reaction->ingredients = collect(explode(',', $halves[0]))
                    ->mapWithKeys(function ($ingredient) {
                        $parts = explode(' ', trim($ingredient));
                        return [$parts[1] => (int)$parts[0]];
                    });
                return [$reaction->result => $reaction];
            });
    }

    public function part1(): string
    {
        return (string)$this->resolve(1);
    }

    public function part2(): string
    {
        $trillion = 1_000_000_000_000;
        $ballpark = floor($trillion / $this->requiredOre);
        return (string)$this->findHighestTrue($ballpark, $ballpark * 4, fn($x) => $this->resolve($x) < $trillion);
    }

    private function resolve(int $amount = 1): int
    {
        $this->requirements = new Collection();
        $this->leftOvers = new Collection();
        $this->requiredOre = 0;
        $this->resolveRequirements('FUEL', $amount);
        $i = 0;
        while ($this->requirements->count() > 0 && $i < 100) {
            $previousGeneration = clone $this->requirements;
            $this->requirements = new Collection();
            $previousGeneration->each(fn($amount, $ingredient) => $this->resolveRequirements($ingredient, $amount));
            $i++;
        }
        return $this->requiredOre;
    }

    private function resolveRequirements(string $ingredient, int $requestedAmount)
    {
        if ($ingredient === 'ORE') {
            $this->requiredOre += $requestedAmount;
            return;
        }

        $fromLeftOver = min($requestedAmount, $this->leftOvers->get($ingredient, 0));
        $requestedAmount -= $fromLeftOver;
        $this->leftOvers->put($ingredient, $this->leftOvers->get($ingredient, 0) - $fromLeftOver);

        $reaction = $this->reactions[$ingredient];
        $amount = ceil($requestedAmount / $reaction->amount) * $reaction->amount;

        $this->leftOvers->put($ingredient, $this->leftOvers->get($ingredient, 0) + $amount - $requestedAmount);

        $batches = $amount / $reaction->amount;

        $this->reactions[$ingredient]
            ->ingredients
            ->each(fn($inAmount, $ingredient) => $this->requireIngredient($ingredient, $inAmount * $batches));
    }

    private function requireIngredient(string $ingredient, int $amount)
    {
        if (!$this->requirements->has($ingredient)) {
            $this->requirements->put($ingredient, 0);
        }
        $this->requirements[$ingredient] += $amount;
    }

    private function findHighestTrue(int $min, int $max, \Closure $test): int
    {
        $center = (int)floor($min + ($max - $min) / 2);

        if ($center === $min || $center === $max) {
            return $center;
        }

        return $test($center)
            ? $this->findHighestTrue($center, $max, $test)
            : $this->findHighestTrue($min, $center, $test);
    }
}
