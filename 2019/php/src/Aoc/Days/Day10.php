<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;

class Day10 extends AbstractDay
{
    private Collection $map;
    private Collection $vectors;
    private Vector $bestPosition;

    public function dayNumber(): int
    {
        return 10;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->map = collect($this->getInputLines())
            ->map(fn($x) => collect(str_split($x)))
            ->map(fn($row, $y) => $row
                ->mapWithKeys(function ($pixel, $x) use ($y) {
                    if ($pixel === '#') {
                        return [$x => new Vector($x, $y)];
                    } else {
                        return [$x => $pixel];
                    }
                })
                ->reject(fn($x) => $x === '.'));

        $this->vectors = $this->map->flatten();
    }

    public function part1(): string
    {
        $bestVector = $this
            ->vectors
            ->map(fn(Vector $vector) => [
                $vector,
                $this
                    ->vectors
                    ->reject(function (Vector $b) use ($vector) {
                        return $b->equals($vector) || $this
                                ->vectors
                                ->first(fn(Vector $c
                                ) => !$c->equals($vector) && !$c->equals($b) && $c->isBetween($vector, $b)
                                ) !== null;
                    })
                    ->count(),
            ])
            ->sortBy(fn($x) => $x[1])
            ->last();

        $this->bestPosition = $bestVector[0];

        return (string)$bestVector[1];
    }

    public function part2(): string
    {
        /** @var Vector $position */
        $position = $this->bestPosition;
        $targets = $this->vectors->reject(fn($x) => $x->equals($position));

        $targetsByAngle = $targets
            ->map(fn($x) => ['angle' => (int)$position->angleTo($x), 'vec' => $x, 'dist' => $position->manhattanTo($x)])
            ->groupBy(fn($x) => $x['angle'])
            ->map(fn($x) => $x->sortBy(fn($y) => $y['dist']))
            ->sortKeys();

        $angles = $targetsByAngle->keys();

        $dead = collect();
        while (true) {
            foreach ($angles as $angle) {
                $target = $targetsByAngle[$angle]->first();
                if ($target === null) {
                    continue;
                }
                $targetsByAngle[$angle] = $targetsByAngle[$angle]->reject(fn($x) => $x['vec']->equals($target['vec']));
                $dead->push($target);
                if ($dead->count() === 200) {
                    return (string)($target['vec']->x * 100 + $target['vec']->y);
                }
            }
        }
    }
}
