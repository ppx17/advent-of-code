<?php

namespace Ppx17\Aoc2018\Days\Day11;


use Ppx17\Aoc2018\Days\Common\Vector;
use Ppx17\Aoc2018\Days\Day;

class Day11 extends Day
{
    private $calculator;
    private $highestPower = 0;
    private $highestLocation;

    public function part1(): string
    {
        $this->highestLocation = new Vector(0, 0);
        $this->calculator = new PowerCalculator($this->data);
        for ($x = 1; $x <= 298; $x++) {
            for ($y = 1; $y <= 298; $y++) {
                $currentLocation = new Vector($x, $y);
                $power = $this->calculator->gridPower($currentLocation);
                if ($power > $this->highestPower) {
                    $this->highestPower = $power;
                    $this->highestLocation = $currentLocation;
                }
            }
        }

        return sprintf("%s,%s", $this->highestLocation->x, $this->highestLocation->y);
    }

    public function part2(): string
    {
        if($this->calculator === null) {
            $this->part1();
        }

        $highestSize = $this->highestPower;
        $lastHighestForSize = 3;
        for ($size = 1; $size <= 17; $size++) {
            // Size 3 was already in the highest vars from part 1.
            if ($size === 3) {
                continue;
            }
            $highestForSize = 0;
            $maxCoordinate = (300 - $size) + 1;
            for ($x = 1; $x <= $maxCoordinate; $x++) {
                for ($y = 1; $y <= $maxCoordinate; $y++) {
                    $currentLocation = new Vector($x, $y);
                    $power = $this->calculator->gridPower($currentLocation, $size);
                    if ($power > $this->highestPower) {
                        $this->highestPower = $power;
                        $this->highestLocation = $currentLocation;
                        $highestSize = $size;
                    }
                    if ($power > $highestForSize) {
                        $highestForSize = $power;
                    }
                }
            }
            // TODO: Not all inputs have a bell curve (see tweakers for sample input)
            if ($highestForSize < $lastHighestForSize) {
                // there seems to be an clear bell curve, when it drops we can stop.
                break;
            }
            $lastHighestForSize = $highestForSize;
        }

        return sprintf("%s,%s,%s", $this->highestLocation->x, $this->highestLocation->y, $highestSize);
    }
}