<?php


namespace Ppx17\Aoc2015\Aoc\Days;


use Illuminate\Support\Collection;

class Day6 extends AbstractDay
{
    private const PATTERN = '#(?<action>toggle|turn) (?<state>on|off|) ?(?<x1>[0-9]+),(?<y1>[0-9]+) through (?<x2>[0-9]+),(?<y2>[0-9]+)#';

    private Collection $instructions;
    private array $grid;

    public function dayNumber(): int
    {
        return 6;
    }

    public function setUp(): void
    {
        parent::setUp();

        $matches = [];
        preg_match_all(self::PATTERN, $this->getInput(), $matches, PREG_SET_ORDER);
        $this->instructions = collect($matches)
            ->map(function ($instruction) {
                $instruction['x1'] = intval($instruction['x1']);
                $instruction['x2'] = intval($instruction['x2']);
                $instruction['y1'] = intval($instruction['y1']);
                $instruction['y2'] = intval($instruction['y2']);
                return $instruction;
            });

        $this->grid = $this->freshGrid();
    }

    public function part1(): string
    {
        return $this->part1String();
        //return $this->part1BooleanArray();
    }

    public function part2(): string
    {
        $this->grid = $this->freshGrid();
        $this
            ->instructions
            ->map(function ($instruction) {
                $offset = ($instruction['action'] === 'toggle') ? 2 : (($instruction['state'] === 'on') ? 1 : -1);
                for ($x = $instruction['x1']; $x <= $instruction['x2']; $x++) {
                    for ($y = $instruction['y1']; $y <= $instruction['y2']; $y++) {
                        $this->grid[$x][$y] = max(0, (int)$this->grid[$x][$y] + $offset);
                    }
                }
            });

        return (string)collect($this->grid)
            ->reduce(fn($i, $row) => $i + array_sum($row), 0);
    }

    private function freshGrid(): array
    {
        $grid = [];
        for ($x = 0; $x < 1000; $x++) {
            $grid[$x] = [];
            for ($y = 0; $y < 1000; $y++) {
                $grid[$x][$y] = false;
            }
        }
        return $grid;
    }

    private function part1BooleanArray(): string
    {
        $this
            ->instructions
            ->each(function (array $instruction) {
                if ($instruction['action'] === 'toggle') {
                    for ($x = $instruction['x1']; $x <= $instruction['x2']; $x++) {
                        for ($y = $instruction['y1']; $y <= $instruction['y2']; $y++) {
                            $this->grid[$x][$y] = !$this->grid[$x][$y];
                        }
                    }
                } else {
                    $s = $instruction['state'] === 'on';
                    for ($x = $instruction['x1']; $x <= $instruction['x2']; $x++) {
                        for ($y = $instruction['y1']; $y <= $instruction['y2']; $y++) {
                            $this->grid[$x][$y] = $s;
                        }
                    }
                }
            });

        return (string)collect($this->grid)
            ->map(fn($row) => collect($row)->countBy()->get(1))
            ->sum();
    }

    /**
     * 555 ms vs 1260ms for boolean array implementation. Probably because toggle with substring on whole rows at a
     * time are a lot faster than toggling all those booleans one by one.
     *
     * Unfortunately we don't have any optimized matrix operations in PHP..
     */
    private function part1String(): string
    {
        $grid = [];
        for ($i = 0; $i < 1000; $i++) {
            $grid[] = str_repeat('0', 1000);
        }

        foreach ($this->instructions as $instruction) {
            if ($instruction['action'] === 'toggle') {
                for ($y = $instruction['y1']; $y <= $instruction['y2']; $y++) {
                    for ($x = $instruction['x1']; $x <= $instruction['x2']; $x++) {
                        $grid[$y][$x] = ($grid[$y][$x] === '1') ? '0' : '1';
                    }
                }
            } else {
                $s = $instruction['state'] === 'on' ? '1' : '0';
                $block = str_repeat($s, ($instruction['x2'] - $instruction['x1']) + 1);
                for ($y = $instruction['y1']; $y <= $instruction['y2']; $y++) {
                    $grid[$y] = (
                        substr($grid[$y], 0, $instruction['x1'])
                        . $block
                        . substr($grid[$y], $instruction['x2'] + 1)
                    );
                }

            }
        }

        return (string)collect($grid)
            ->map(fn($row) => substr_count($row, '1'))
            ->sum();
    }
}
