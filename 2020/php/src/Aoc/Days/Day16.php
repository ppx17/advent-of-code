<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Illuminate\Support\Collection;

class Day16 extends AbstractDay
{
    private Collection $rules;
    private Collection $nearbyTickets;

    private array $myTicket;

    public function dayNumber(): int
    {
        return 16;
    }

    public function setUp(): void
    {
        preg_match_all(
            "#^(?<field>[a-z ]+): (?<r1min>\d+)-(?<r1max>\d+) or (?<r2min>\d+)-(?<r2max>\d+)$#m",
            $this->getInputTrimmed(), $matches, PREG_SET_ORDER
        );

        $this->rules = collect($matches);

        $parts = explode('nearby tickets:', $this->getInputTrimmed());
        $this->nearbyTickets = collect(explode("\n", $parts[1]))
            ->reject(fn($t) => empty($t))
            ->map(fn($t) => collect(explode(',', $t))->map(fn($x) => (int)$x));

        $parts = explode('your ticket:', $parts[0]);

        $this->myTicket = explode(',', trim($parts[1]));
    }

    public function part1(): string
    {
        return $this->nearbyTickets
            ->map(fn($t) => $t->filter(fn($n) => $this->matchesAnyRule($n))->sum())
            ->sum();
    }

    public function part2(): string
    {
        $validTickets = $this->nearbyTickets
            ->reject(fn($t) => !is_null($t->first(fn($n) => $this->matchesAnyRule($n))));

        $options = $this->rules->mapWithKeys(fn($r) => [$r['field'] => $this->rules
            ->map(fn($x, $index) => $validTickets->filter(fn($t) => $this->numberMatchesRule($t[$index], $r)))
            ->reject(fn($fit) => $fit->count() !== $validTickets->count())
            ->keys()]);

        $places = [];
        foreach ($options as $field => $possiblePlaces) {
            foreach ($possiblePlaces as $spot) {
                $places[$spot] ??= new Collection();
                $places[$spot]->push($field);
            }
        }

        $certain = new Collection();
        for ($i = 0; $i < $this->rules->count(); $i++) {
            foreach ($places as $index => $options) {
                if ($options->count() === 1) {
                    $label = $options->first();
                    $certain->put($label, $index);

                    foreach ($places as $otherIdx => $otherPlace) {
                        if ($otherIdx === $index) continue;
                        $places[$otherIdx] = $otherPlace->reject(fn($x) => $x === $label);
                    }
                    unset($places[$index]);
                }
            }
        }

        return array_product($certain
            ->flip()
            ->filter(fn($r) => substr($r, 0, 9) === 'departure')
            ->map(fn($val, $idx) => (int)$this->myTicket[$idx])
            ->toArray());
    }

    private function matchesAnyRule(int $number): bool
    {
        return $this->rules
            ->first(fn($r) => $this->numberMatchesRule($number, $r)) === null;
    }

    private function numberMatchesRule(int $number, array $rule): bool
    {
        return ($number >= $rule['r1min'] && $number <= $rule['r1max'])
            || ($number >= $rule['r2min'] && $number <= $rule['r2max']);
    }
}
