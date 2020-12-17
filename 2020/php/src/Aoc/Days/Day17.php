<?php


namespace Ppx17\Aoc2020\Aoc\Days;


class Day17 extends AbstractDay
{
    private const CYCLES = 6;
    private array $map;
    private array $originalMap;

    public function dayNumber(): int
    {
        return 17;
    }

    public function setUp(): void
    {
        $this->originalMap = [
            array_map(fn($y) => array_map(fn($x) => $x === '#', str_split($y)), $this->getInputLines())
        ];

        $this->map = [];
    }

    public function part1(): string
    {
        $this->map = $this->originalMap;

        // z, y, x
        $topLeft = [0, 0, 0];
        $bottomRight = [0, count($this->map[0]), count($this->map[0][0])];

        for ($cycle = 0; $cycle < self::CYCLES; $cycle++) {
            $topLeft = array_map(fn($x) => $x - 1, $topLeft);
            $bottomRight = array_map(fn($x) => $x + 1, $bottomRight);

            $newMap = [];
            for ($z = $topLeft[0]; $z <= $bottomRight[0]; $z++) {
                for ($y = $topLeft[1]; $y <= $bottomRight[1]; $y++) {
                    for ($x = $topLeft[2]; $x <= $bottomRight[2]; $x++) {
                        $anc = $this->neighbors3($z, $y, $x);
                        $cur = $this->map[$z][$y][$x] ?? false;
                        $newMap[$z][$y][$x] = ($cur && ($anc === 2 || $anc === 3)) || (!$cur && $anc === 3);
                    }
                }
            }
            $this->map = $newMap;
        }

        return $this->numActive($this->map);
    }

    public function part2(): string
    {
        $this->map = [$this->originalMap];
        // w, z, y, x
        $topLeft = [0, 0, 0, 0];
        $bottomRight = [0, 0, count($this->map[0][0]), count($this->map[0][0][0])];

        for ($cycle = 0; $cycle < self::CYCLES; $cycle++) {
            $topLeft = array_map(fn($x) => $x - 1, $topLeft);
            $bottomRight = array_map(fn($x) => $x + 1, $bottomRight);

            $newMap = [];
            for ($w = $topLeft[0]; $w <= $bottomRight[0]; $w++) {
                for ($z = $topLeft[1]; $z <= $bottomRight[1]; $z++) {
                    for ($y = $topLeft[2]; $y <= $bottomRight[2]; $y++) {
                        for ($x = $topLeft[3]; $x <= $bottomRight[3]; $x++) {
                            $anc = $this->neighbors4($w, $z, $y, $x);
                            $cur = $this->map[$w][$z][$y][$x] ?? false;
                            $newMap[$w][$z][$y][$x] = ($cur && ($anc === 2 || $anc === 3)) || (!$cur && $anc === 3);
                        }
                    }
                }
            }
            $this->map = $newMap;
        }

        return $this->numActive($this->map);
    }

    private function neighbors3(int $z, int $y, int $x): int
    {
        $activeNeighbors = 0;
        for ($nz = $z - 1; $nz <= $z + 1; $nz++) {
            for ($ny = $y - 1; $ny <= $y + 1; $ny++) {
                for ($nx = $x - 1; $nx <= $x + 1; $nx++) {
                    $activeNeighbors += (
                        !($nz === $z && $ny === $y && $nx === $x)
                        && ($this->map[$nz][$ny][$nx] ?? false)
                    );
                }
            }
        }

        return $activeNeighbors;
    }

    private function neighbors4(int $w, int $z, int $y, int $x): int
    {
        $activeNeighbors = 0;
        for ($nw = $w - 1; $nw <= $w + 1; $nw++) {
            for ($nz = $z - 1; $nz <= $z + 1; $nz++) {
                for ($ny = $y - 1; $ny <= $y + 1; $ny++) {
                    for ($nx = $x - 1; $nx <= $x + 1; $nx++) {
                        $activeNeighbors += (
                            !(($nw === $w && $nz === $z && $ny === $y && $nx === $x)) &&
                            ($this->map[$nw][$nz][$ny][$nx] ?? false)
                        );
                    }
                }
            }
        }

        return $activeNeighbors;
    }

    private function numActive($list): int
    {
        if(is_array($list[0])) {
            return array_sum(array_map(fn($x) => $this->numActive($x), $list));
        }

        return array_sum($list);
    }
}
