<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Illuminate\Support\Collection;

class Day8 extends AbstractDay
{
    private const IMAGE_WIDTH = 25;
    private const IMAGE_HEIGHT = 6;
    private Collection $layers;

    public function dayNumber(): int
    {
        return 8;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->layers = collect(str_split(trim($this->getInput())))
            ->map(fn($x) => intval($x))
            ->chunk(self::IMAGE_WIDTH * self::IMAGE_HEIGHT);
    }

    public function part1(): string
    {
        $counted = $this
            ->layers
            ->map(function(Collection $layer) {
                return $layer->countBy(fn($x) => $x);
            });

        $leastZeroes = $counted
            ->min(fn($layer) => $layer[0]);

        $layer = $counted
            ->filter(fn($x) => $x[0] === $leastZeroes)
            ->first();

        return $layer[1] * $layer[2];
    }

    public function part2(): string
    {
        $image = [];
        $this->layers->each(function ($layer) use (&$image) {
            foreach ($layer->values() as $pos => $color) {
                if(!isset($image[$pos]) || $image[$pos] == 2) {
                    $image[$pos] = $color;
                }
            }
        });
        return $this->render($image);
    }

    private function render(array $image): string
    {
        return collect($image)
            ->chunk(self::IMAGE_WIDTH)
            ->map(fn($line) => $line
                ->map(fn($pixel) => ($pixel === 1) ? '#' : ' ')
                ->join(''))
            ->join("\n");
    }
}
