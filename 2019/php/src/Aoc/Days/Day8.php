<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Illuminate\Support\Collection;

class Day8 extends AbstractDay
{
    private const IMAGE_WIDTH = 25;
    private const IMAGE_HEIGHT = 6;
    private Collection $layers;
    private array $image;

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
        $layer = $this
            ->layers
            ->map(fn(Collection $layer) => $layer->countBy())
            ->sortBy(fn(Collection $x) => $x->get(0))
            ->first();

        return $layer[1] * $layer[2];
    }

    public function part2(): string
    {
        $this->image = [];
        $this->layers->each(fn($layer) => $this->applyLayer($layer));
        return $this->render();
    }

    private function applyLayer(Collection $layer): void
    {
        foreach ($layer->values() as $pos => $color) {
            if (!isset($this->image[$pos]) || $this->image[$pos] == 2) {
                $this->image[$pos] = $color;
            }
        }
    }

    private function render(): string
    {
        return collect($this->image)
            ->chunk(self::IMAGE_WIDTH)
            ->map(fn($line) => $line
                ->map(fn($pixel) => ($pixel === 1) ? '#' : ' ')
                ->join(''))
            ->join("\n");
    }
}
