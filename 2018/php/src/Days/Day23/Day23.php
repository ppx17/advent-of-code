<?php

namespace Ppx17\Aoc2018\Days\Day23;

use Ppx17\Aoc2018\Days\Day;

class Day23 extends Day
{
    private $bots = [];

    public function __construct(string $data)
    {
        parent::__construct($data);

        preg_match_all(
            '/pos=<\s*(?<x>-?\d+),\s*(?<y>-?\d+),\s*(?<z>-?\d+)>, r=(?<r>\d+)/',
            $this->data,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $this->bots[] = new Bot(new Vector3($match['x'], $match['y'], $match['z']), $match['r']);
        }
    }

    public function part1(): string
    {
        usort($this->bots, function (Bot $a, Bot $b) {
            return $b->getRange() - $a->getRange();
        });

        /** @var Bot $strongestBot */
        $strongestBot = $this->bots[0];
        $inRangeCount = 0;
        foreach ($this->bots as $bot) {
            if ($strongestBot->inRange($bot->getLocation())) {
                $inRangeCount++;
            }
        }

        return $inRangeCount;
    }

    public function part2(): string
    {
        $xs = array_map(function (Bot $bot) {
            return $bot->getLocation()->x;
        }, $this->bots);
        $ys = array_map(function (Bot $bot) {
            return $bot->getLocation()->y;
        }, $this->bots);
        $zs = array_map(function (Bot $bot) {
            return $bot->getLocation()->z;
        }, $this->bots);

        for($scale = 1; $scale < (max($xs) - min($xs)); $scale *= 2);

        while (true) {
            $bestInRangeCount = 0;
            $bestPosition = null;
            for ($x = min($xs); $x <= max($xs); $x += $scale) {
                for ($y = min($ys); $y <= max($ys); $y += $scale) {
                    for ($z = min($zs); $z <= max($zs); $z += $scale) {
                        $inRangeCount = 0;
                        foreach ($this->bots as $bot) {
                            if ($bot->inRangeInt($x, $y, $z)) {
                                $inRangeCount++;
                            }
                        }

                        if ($inRangeCount > $bestInRangeCount) {
                            $bestInRangeCount = $inRangeCount;
                            $bestPosition = new Vector3($x, $y, $z);
                        }
                    }
                }
            }
            if ($scale === 1) {
                $target = new Vector3($x, $y, $z);
                $verifyCount = 0;
                foreach ($this->bots as $bot) {
                    if ($bot->inRange($target)) {
                        $verifyCount++;
                    }
                }

                return (string)$bestPosition->manhattanDistanceInt(0, 0, 0);;
            }

            // Narrow the scale down and search the spread of the previous scale around the bestPosition position
            $xs = [$bestPosition->x - $scale, $bestPosition->x + $scale];
            $ys = [$bestPosition->y - $scale, $bestPosition->y + $scale];
            $zs = [$bestPosition->z - $scale, $bestPosition->z + $scale];
            $scale = (int)floor($scale / 2);
        }
    }
}