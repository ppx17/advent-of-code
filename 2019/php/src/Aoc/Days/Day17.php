<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;
use Ppx17\Aoc2019\Aoc\Days\Day13\IntCode;
use Ppx17\Aoc2019\Aoc\Days\Day15\Direction;
use Ppx17\Aoc2019\Aoc\Days\Day17\Map;
use Ppx17\Aoc2019\Aoc\Days\Day17\PathFinder;

class Day17 extends AbstractDay
{
    private IntCode $computer;
    private Map $map;
    private Vector $position;
    private Collection $directions;

    public function dayNumber(): int
    {
        return 17;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->computer = new IntCode($this->getInputIntCode());
        $this->map = new Map();
        $this->position = new Vector(0, 0);
        $this->directions = collect([
            new Direction(0, 1),
            new Direction(0, -1),
            new Direction(1, 0),
            new Direction(-1, 0),
        ]);

        $this->computer->outputCallable = fn($x) => $this->addChar(chr($x));
        $this->computer->run();
    }

    public function part1(): string
    {
        $intersections = collect();
        $this->map->each(function ($vec, $color) use (&$intersections) {
            if ($color !== '#') {
                return;
            }
            $other = $this->directions->first(fn($x) => $this->map->color($vec->add($x)) !== '#');
            if ($other !== null) {
                return;
            }
            $intersections->push(clone $vec);
        });

        return $intersections
            ->map(fn($vec) => $vec->x * $vec->y)
            ->sum();
    }

    public function part2(): string
    {
        $pathFinder = new PathFinder();
        $pathFinder->position = new Vector(4, 0);
        $pathFinder->direction = Direction::up();
        $pathFinder->computer = $this->computer;
        $pathFinder->map = $this->map;

        $path = $pathFinder->path();

        [$a, $b, $c] = $this->findABC($path);

        $mainRoutine = $this->findMainRoutine($path, $a, $b, $c);

        $this->computer->reset();
        $this->computer->memory[0] = 2;
        $this->computer->inputList =
            $this->convertAscii($mainRoutine->join(','))
                ->concat($this->convertAscii($a))
                ->concat($this->convertAscii($b))
                ->concat($this->convertAscii($c))
                ->concat($this->convertAscii('n'))
                ->toArray();


        $this->computer->run();

        return $this->computer->output;
    }

    private function addChar(string $chr)
    {
        if ($chr === "\n") {
            $this->position->y += 1;
            $this->position->x = 0;
        } else {
            $this->map->paint($this->position, $chr);
            $this->position->x++;
        }
    }

    private function findABC(Collection $path): array
    {
        $pathString = $path->join(',');

        $parts = collect();
        for ($size = 2; $size < 12; $size += 2) {
            for ($i = 0; $i < $path->count() - $size; $i += 2) {
                $part = $path->slice($i, $size)->join(',');

                $parts->put($part, substr_count($pathString, $part));
            }
        }

        $parts = $parts->sort()->reverse();

        foreach ($parts as $a => $ac) {
            foreach ($parts as $b => $bc) {
                foreach ($parts as $c => $cc) {
                    if ($a === $b || $b === $c || $a === $c) {
                        continue;
                    }

                    if (str_replace([$a, $b, $c, ','], '', $pathString) === '') {
                        return [$a, $b, $c];
                    }
                }
            }
        }
        return null;
    }

    private function findMainRoutine(Collection $path, $a, $b, $c)
    {
        $parts = collect([
            'A' => $a,
            'B' => $b,
            'C' => $c,
        ]);
        $pathString = $path->join(',');

        $result = collect();

        $tick = 0;
        while (strlen($pathString) > 0 && $tick < 10_000) {
            $tick++;
            foreach ($parts as $letter => $part) {
                if (strpos($pathString, $part) === 0) {
                    $pathString = substr($pathString, strlen($part) + 1);
                    $result->push($letter);
                }
            }
        }
        return $result;
    }

    private function convertAscii(string $join)
    {
        $join .= "\n";
        return collect(str_split($join))
            ->map(fn($x) => ord($x));
    }
}
