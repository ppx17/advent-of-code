<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Illuminate\Support\Collection;

class Day7 extends AbstractDay
{
    private Collection $bags;

    public function dayNumber(): int
    {
        return 7;
    }

    public function setUp(): void
    {
        preg_match_all(
            "#^(?<bag>\w+ \w+) bags contain (?<content>(\d+ \w+ \w+ bags?,? ?)+)\.$#m",
            $this->getInput(),
            $matches,
            PREG_SET_ORDER
        );
        $this->bags = collect($matches)
            ->mapWithKeys(fn($m) => [$m['bag'] => $m['content']])
            ->map(function ($content) {
                preg_match_all('#(?<num>\d+) (?<color>\w+ \w+) bags?#', $content, $matches, PREG_SET_ORDER);
                return collect($matches)
                    ->mapWithKeys(fn($match) => [$match['color'] => (int)$match['num']]);
            });
    }

    public function part1(): string
    {
        return $this->countOptions('shiny gold');
    }

    private function countOptions(string $color, ?Collection $seen = null): int
    {
        $seen ??= collect();
        $this->bags
            ->each(function (Collection $bag, $bagColor) use ($color, $seen) {
                if ($bag->has($color)) {
                    $seen->put($bagColor, true);
                    $this->countOptions($bagColor, $seen);
                }
            });
        return $seen->count();
    }

    public function part2(): string
    {
        return $this->countAllBags('shiny gold');
    }

    private function countAllBags($color): int
    {
        return $this->bags
            ->get($color, collect())
            ->map(function ($amount, $containingColor) {
                return $amount + ($amount * $this->countAllBags($containingColor));
            })
            ->sum();
    }
}
