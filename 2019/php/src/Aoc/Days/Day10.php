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
        $len = $this->vectors->count();

        $canSee = []; // pre-filling with zeroes it is actually slower...

        for($ai = 0; $ai < $len - 1; $ai++) {
            for($bi = $ai + 1; $bi < $len; $bi++) {
                foreach ($this->vectors as $ci => $c) {
                    if($bi === $ci || $ai === $ci) continue;
                    if($c->isBetween($this->vectors[$ai], $this->vectors[$bi]))
                    {
                        continue 2;
                    }
                }
                $canSee[$ai]++;
                $canSee[$bi]++;
            }
        }
        $bestView = max($canSee);
        $this->bestPosition = $this->vectors[array_search($bestView, $canSee)];
        return (string)$bestView;
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
