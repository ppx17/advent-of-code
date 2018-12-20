<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 15:36
 */

namespace Ppx17\Aoc2018\Days\Day11;


use Ppx17\Aoc2018\Days\Common\Vector;

class PowerCalculator
{
    private $serial;
    private $cache = [];
    private $twoByTwo = [];
    private $threeByThree = [];

    public function __construct(int $serial)
    {
        $this->serial = $serial;
    }

    public function cellPower(int $x, int $y): int
    {
        if (isset($this->cache[$x][$y])) {
            return $this->cache[$x][$y];
        }
        $rackId = $x + 10;
        $power = (($rackId * $y) + $this->serial) * $rackId;
        $power = ($power < 100 ? 0 : ($power / 100 % 10)) - 5;
        $this->cache[$x][$y] = $power;
        return $power;
    }

    public function gridPower(Vector $location, int $size = 3): int
    {
        $sum = 0;
        if ($size > 3) {
            if ($size % 3 === 0) {
                for ($addX = 0; $addX < $size; $addX += 3) {
                    for ($addY = 0; $addY < $size; $addY += 3) {
                        $sum += $this->threeByThree[$location->x + $addX][$location->y + $addY];
                    }
                }
                return $sum;
            } elseif ($size % 2 === 0) {
                for ($addX = 0; $addX < $size; $addX += 2) {
                    for ($addY = 0; $addY < $size; $addY += 2) {
                        $sum += $this->twoByTwo[$location->x + $addX][$location->y + $addY];
                    }
                }
                return $sum;
            }
        }

        for ($addX = 0; $addX < $size; $addX++) {
            for ($addY = 0; $addY < $size; $addY++) {
                $sum += $this->cellPower($location->x + $addX, $location->y + $addY);
            }
        }
        if ($size === 2) {
            $this->twoByTwo[$location->x][$location->y] = $sum;
        } elseif ($size === 3) {
            $this->threeByThree[$location->x][$location->y] = $sum;
        }
        return $sum;
    }
}