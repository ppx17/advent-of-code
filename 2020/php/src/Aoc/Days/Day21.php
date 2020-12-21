<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Illuminate\Support\Collection;

class Day21 extends AbstractDay
{
    private Collection $food;
    private Collection $map;

    public function dayNumber(): int
    {
        return 21;
    }

    public function setUp(): void
    {
        preg_match_all("#^([a-z ]+)\(contains ([a-z, ]+)\)$#m", $this->getInputTrimmed(), $matches, PREG_SET_ORDER);

        $this->food = collect($matches)
            ->map(fn($m) => ['ingr' => explode(' ', trim($m[1])), 'al' => explode(', ', trim($m[2]))]);

        $a2f = $this->food
            ->pluck('al')
            ->flatten()
            ->unique()
            ->flip()
            ->map(fn($v, $al) => collect());

        $this->food
            ->each(function ($food) use (&$a2f) {
                foreach ($food['al'] as $al) {
                    foreach ($food['ingr'] as $in) {
                        $a2f[$al]->push($in);
                    }
                }
            });

        $optionCount = $a2f->map(fn($x) => $x->countBy(fn($x) => $x)->sortDesc());

        $this->map = collect();

        for ($i = 0; $i < $a2f->count(); $i++) {
            foreach ($optionCount as $allergen => $options) {
                $highest = $options->max();
                $highestOptions = $options->filter(fn($x) => $x === $highest);

                if ($highestOptions->count() === 1) {
                    $ingredient = $highestOptions->keys()->first();
                    $this->map->put($allergen, $ingredient);
                    $optionCount = $optionCount->reject(fn($o, $a) => $a === $allergen)
                        ->map(fn($ol) => $ol->reject(fn($c, $i) => $i === $ingredient));
                    break;
                }
            }
        }
    }

    public function part1(): string
    {
        return $this->food
            ->pluck('ingr')
            ->flatten()
            ->filter(fn($in) => $this->map->search($in) === false)
            ->count();
    }

    public function part2(): string
    {
        return $this->map
            ->sortKeys()
            ->join(',');
    }
}
