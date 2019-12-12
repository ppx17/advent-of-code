<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Days\Day12\Moon;
use Ppx17\Aoc2019\Aoc\Days\Day12\Vector;

class Day12 extends AbstractDay
{
    private const SIMULATION_STEPS = 1000;
    private Collection $bodies;

    public function dayNumber(): int
    {
        return 12;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->bodies = $this->loadBodies();
    }

    public function part1(): string
    {
        for ($step = 0; $step < self::SIMULATION_STEPS; $step++) {
            $this->simulateStep();
        }

        return (string)$this->bodies->map(fn(Moon $moon) => $moon->energy())->sum();
    }

    public function part2(): string
    {
        $vector = $this->findLoopsPerAxis();
        return $this->lcm($vector->toArray());
    }

    private function findLoopsPerAxis(): Vector
    {
        $this->bodies = $this->loadBodies();
        $x = $y = $z = null;
        $seenX = $seenY = $seenZ = [];
        $seenX[$this->snapAxes('x')] = true;
        $seenY[$this->snapAxes('y')] = true;
        $seenZ[$this->snapAxes('z')] = true;
        for ($step = 1; $step < 1_000_000; $step++) {
            $this->simulateStep();

            if (is_null($x)) {
                $snapX = $this->snapAxes('x');
                if (isset($seenX[$snapX])) {
                    $x = $step;
                }
                $seenX[$snapX] = true;
            }

            if (is_null($y)) {
                $snapY = $this->snapAxes('y');
                if (isset($seenY[$snapY])) {
                    $y = $step;
                }
                $seenY[$snapY] = true;
            }

            if (is_null($z)) {
                $snapZ = $this->snapAxes('z');
                if (isset($seenZ[$snapZ])) {
                    $z = $step;
                }
                $seenZ[$snapZ] = true;
            }

            if (isset($x) && isset($y) && isset($z)) {
                break;
            }
        }

        return new Vector($x, $y, $z);
    }

    private function snapAxes(string $axes): string
    {
        return $this->bodies
            ->map(fn(Moon $b) => $b->location->{$axes} . ':' . $b->velocity->{$axes})
            ->join("#");
    }

    private function simulateStep(): void
    {
        for ($a = 0; $a < $this->bodies->count() - 1; $a++) {
            for ($b = $a + 1; $b < $this->bodies->count(); $b++) {
                $deltaA = $this->bodies[$a]->getDeltaV($this->bodies[$b]);
                $deltaB = $this->bodies[$b]->getDeltaV($this->bodies[$a]);
                $this->bodies[$a]->velocity = $this->bodies[$a]->velocity->add($deltaA);
                $this->bodies[$b]->velocity = $this->bodies[$b]->velocity->add($deltaB);
            }
        }
        $this->bodies->each(fn(Moon $moon) => $moon->move());
    }

    private function loadBodies(): Collection
    {
        return collect($this->getInputLines())
            ->map(function (string $x) {
                $matches = [];
                preg_match("#<x=(?<x>[0-9-]+), y=(?<y>[0-9-]+), z=(?<z>[0-9-]+)>#", $x, $matches);
                return new Moon(new Vector($matches['x'], $matches['y'], $matches['z']));
            });
    }

    private function lcm(array $parts): int
    {
        $primeCounts = [];

        collect($parts)
            ->each(function (int $x) use (&$primeCounts) {
                $this->primeFactors($x)
                    ->countBy()
                    ->each(function ($x, $y) use (&$primeCounts) {
                        return $primeCounts[$y] = ($primeCounts[$y] > $x) ? $primeCounts[$y] : $x;
                    });
            });

        return collect($primeCounts)
            ->map(fn($x, $y) => $y ** $x)
            ->reduce(fn($x, $y) => $x * $y, 1);
    }

    private function primeFactors(int $number): Collection
    {
        $factors = new Collection();
        while ($number > 0 && !$this->isPrime($number)) {
            for ($i = 2; $i <= sqrt($number); $i++) {
                if (!$this->isPrime($i)) {
                    continue;
                }
                if ($number % $i === 0) {
                    $factors->push($i);
                    $number /= $i;
                }
            }
        }
        if ($number > 1) {
            $factors->push($number);
        }

        return $factors;
    }

    private function isPrime(int $number): bool
    {
        if ($number <= 1) {
            return false;
        }
        for ($i = 2; $i <= sqrt($number); $i++) {
            if ($number % $i === 0) {
                return false;
            }
        }
        return true;
    }
}
