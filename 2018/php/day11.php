<?php
$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");
require_once 'helpers.php';
$serial = intval($data);

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

    function gridPower(int $x, int $y, int $size = 3): int
    {
        $sum = 0;
        if (true) {
            if ($size > 3) {
                if ($size % 3 === 0) {
                    for ($addX = 0; $addX < $size; $addX += 3) {
                        for ($addY = 0; $addY < $size; $addY += 3) {
                            $sum += $this->threeByThree[$x + $addX][$y + $addY];
                        }
                    }
                    return $sum;
                } elseif ($size % 2 === 0) {
                    for ($addX = 0; $addX < $size; $addX += 2) {
                        for ($addY = 0; $addY < $size; $addY += 2) {
                            $sum += $this->twoByTwo[$x + $addX][$y + $addY];
                        }
                    }
                    return $sum;
                }
            }
        }
        for ($addX = 0; $addX < $size; $addX++) {
            for ($addY = 0; $addY < $size; $addY++) {
                $sum += $this->cellPower($x + $addX, $y + $addY);
            }
        }
        if ($size === 2) {
            $this->twoByTwo[$x][$y] = $sum;
        } elseif ($size === 3) {
            $this->threeByThree[$x][$y] = $sum;
        }
        return $sum;
    }
}

$s = ms();
$highest = 0;
$highestX = 0;
$highestY = 0;
$calculator = new PowerCalculator($serial);
for ($x = 1; $x <= 298; $x++) {
    for ($y = 1; $y <= 298; $y++) {
        $power = $calculator->gridPower($x, $y);
        if ($power > $highest) {
            $highest = $power;
            $highestX = $x;
            $highestY = $y;
        }
    }
}

printf("Part 1: %s,%s\n", $highestX, $highestY);
msp('part 1', ms($s));

$p2s = ms();
$highestSize = $highest;
$lastHighestForSize = 3;
for ($size = 1; $size <= 300; $size++) {
    $s = ms();
    // Size 3 was already in the highest vars from part 1.
    if ($size === 3) {
        continue;
    }
    $highestForSize = 0;
    $maxCoord = (300 - $size) + 1;
    for ($x = 1; $x <= $maxCoord; $x++) {
        for ($y = 1; $y <= $maxCoord; $y++) {
            $power = $calculator->gridPower($x, $y, $size);
            if ($power > $highest) {
                $highest = $power;
                $highestX = $x;
                $highestY = $y;
                $highestSize = $size;
            }
            if ($power > $highestForSize) {
                $highestForSize = $power;
            }
        }
    }

    debug("%s: %s in %.2f ms", $size, $highestForSize, ms($s));
    if ($highestForSize < $lastHighestForSize) {
        // there seems to be an clear bell curve, when it drops we can stop.
        break;
    }
    $lastHighestForSize = $highestForSize;
}

printf("Part 2: %s,%s,%s\n", $highestX, $highestY, $highestSize);

msp('part 2', ms($p2s));