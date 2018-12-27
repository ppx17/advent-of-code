<?php

namespace Ppx17\Aoc2018\Days\Day11;


class PowerCalculator
{
    public $summedAreaTable = [];
    private $serial;

    public function __construct(int $serial, int $size = 300)
    {
        $this->serial = $serial;
        $this->summedAreaTable = [];
        for ($x = 1; $x <= $size; $x++) {
            for ($y = 1; $y <= $size; $y++) {
                if ($x > 1 && $y > 1) {
                    $this->summedAreaTable[$x][$y] = $this->cellPower($x, $y)
                        + $this->summedAreaTable[$x - 1][$y]
                        + $this->summedAreaTable[$x][$y - 1]
                        - $this->summedAreaTable[$x - 1][$y - 1];
                } elseif ($x > 1) {
                    $this->summedAreaTable[$x][$y] = $this->cellPower($x, $y)
                        + $this->summedAreaTable[$x - 1][$y];
                } elseif ($y > 1) {
                    $this->summedAreaTable[$x][$y] = $this->cellPower($x, $y)
                        + $this->summedAreaTable[$x][$y - 1];
                } else {
                    $this->summedAreaTable[$x][$y] = $this->cellPower($x, $y);
                }
            }
        }
    }

    public function cellPower(int $x, int $y): int
    {
        $rackId = $x + 10;
        $power = (($rackId * $y) + $this->serial) * $rackId;
        return ($power < 100 ? 0 : ($power / 100 % 10)) - 5;
    }

    public function gridPower(int $tlX, int $tlY, int $size = 3): int
    {
        $brX = $tlX + ($size - 1);
        $brY = $tlY + ($size - 1);
        return $this->summedAreaTable[$brX][$brY]
            - $this->summedAreaTable[$brX][$tlY - 1]
            - $this->summedAreaTable[$tlX - 1][$brY]
            + $this->summedAreaTable[$tlX - 1][$tlY - 1];
    }
}