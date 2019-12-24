<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Illuminate\Support\Collection;

class Day24 extends AbstractDay
{
    private array $grid;
    private int $w;
    private int $h;
    private Collection $directions;

    public function dayNumber(): int
    {
        return 24;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->grid = collect($this->getInputLines())->map(fn($x) => str_split($x))->toArray();
        $this->w = count($this->grid[0]);
        $this->h = count($this->grid);
        $this->directions = collect([[0, 1], [0, -1], [1, 0], [-1, 0]]);
    }

    public function part1(): string
    {
        $grid = $this->grid;
        $seen = [];
        $div = 0;
        while (!isset($seen[$div])) {
            $seen[$div] = true;
            $new = [];
            $div = 0;
            $val = 1;
            for ($y = 0; $y < $this->h; $y++) {
                for ($x = 0; $x < $this->w; $x++) {
                    $c = $this->directions->map(fn($a) => ($grid[$y + $a[0]][$x + $a[1]] ?? '') === '#' ? 1 : 0)->sum();
                    $new[$y][$x] = ($c === 1 && $grid[$y][$x] === '#') ? '#' : (($c === 1 || $c === 2 && $grid[$y][$x] !== '#') ? '#' : '.');
                    $div += $new[$y][$x] === '#' ? $val : 0;
                    $val <<= 1;
                }
            }
            $grid = $new;
        }

        return $div;
    }

    public function part2(): string
    {
        $grids = [$this->grid];
        $grids[0][2][2] = '?';
        for ($i = 0; $i < 200; $i++) {
            $grids = $this->evolveLevels($grids);
        }
        $i = 0;
        array_walk_recursive($grids, function ($x) use (&$i) {
            $i += ($x === '#') ? 1 : 0;
        });
        return (string)$i;
    }

    private function evolveLevels(array $grids): array
    {
        // subgrid = positive
        // supergrid = negative
        $newGrids = [];
        $next = ((count($grids) - 1) / 2) + 1;
        $grids[$next] = $this->newLevel();
        $grids[-$next] = $this->newLevel();
        foreach ($grids as $level => $grid) {
            $new = [];
            for ($y = 0; $y < $this->h; $y++) {
                $new[$y] = [];
                for ($x = 0; $x < $this->w; $x++) {
                    if ($x === 2 && $y === 2) {
                        $new[$y][$x] = '?';
                        continue;
                    }
                    $surrounding =
                        ($grid[$y][$x - 1] ?? '')
                        . ($grid[$y][$x + 1] ?? '')
                        . ($grid[$y - 1][$x] ?? '')
                        . ($grid[$y + 1][$x] ?? '');

                    if (isset($grids[$level + 1])) {
                        $subGrid = $grids[$level + 1];
                        if ($x === 2 && $y === 1) {
                            // top
                            $surrounding .= join('', $subGrid[0]);
                        } elseif ($x === 2 && $y === 3) {
                            // bottom
                            $surrounding .= join('', $subGrid[4]);
                        } elseif ($x === 1 && $y === 2) {
                            // left
                            for ($subY = 0; $subY < $this->h; $subY++) {
                                $surrounding .= $subGrid[$subY][0];
                            }
                        } elseif ($x === 3 && $y === 2) {
                            // right
                            for ($subY = 0; $subY < $this->h; $subY++) {
                                $surrounding .= $subGrid[$subY][4];
                            }
                        }
                    }
                    if (isset($grids[$level - 1])) {
                        $superGrid = $grids[$level - 1];
                        if ($y === 0) {
                            // top
                            $surrounding .= $superGrid[1][2];
                        } elseif ($y === 4) {
                            // bottom
                            $surrounding .= $superGrid[3][2];
                        }
                        if ($x === 0) {
                            // left
                            $surrounding .= $superGrid[2][1];
                        } elseif ($x === 4) {
                            // right
                            $surrounding .= $superGrid[2][3];
                        }
                    }

                    $neighbors = substr_count($surrounding, '#');
                    if ($grid[$y][$x] === '#') {
                        // bug dies unless 1 neighbor
                        $new[$y][$x] = ($neighbors === 1) ? '#' : '.';
                    } else {
                        // gets invested with 1 or two neighbors
                        $new[$y][$x] = ($neighbors === 1 || $neighbors === 2) ? '#' : '.';
                    }
                }
            }
            $newGrids[$level] = $new;
        }
        return $newGrids;
    }

    private function newLevel(): array
    {
        return [
            ['.', '.', '.', '.', '.'],
            ['.', '.', '.', '.', '.'],
            ['.', '.', '?', '.', '.'],
            ['.', '.', '.', '.', '.'],
            ['.', '.', '.', '.', '.'],
        ];
    }
}
