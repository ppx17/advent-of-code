<?php

namespace Ppx17\Aoc2018\Days\Day15;


class Sort
{
    public static function unitsByReadingOrder(array &$units)
    {
        usort($units, function (Unit $a, Unit $b) {
            if ($a->location->y !== $b->location->y) {
                return $a->location->y - $b->location->y;
            }
            return $a->location->x - $b->location->x;
        });
    }

    public static function enemiesByHitPoints(array &$units)
    {
        usort($units, function (Unit $a, Unit $b) {
            return $a->hitPoints - $b->hitPoints;
        });
    }

    public static function pathsByLengthAndReadingOrder(array &$paths)
    {
        usort($paths, function (array $a, array $b) {
            $countA = count($a);
            $countB = count($b);
            if ($countA != $countB) {
                // Paths not same length, prefer shortest path.
                return $countA - $countB;
            }

            // Paths of same length, choose destination in reading order
            if (!$a[$countA - 1]->getLocation()->equals($b[$countB - 1]->getLocation())) {
                if ($a[$countA - 1]->getLocation()->y !== $b[$countB - 1]->getLocation()->y) {
                    // first top down...
                    return $a[$countA - 1]->getLocation()->y - $b[$countB - 1]->getLocation()->y;
                }
                // Then left to right
                return $a[$countA - 1]->getLocation()->x - $b[$countB - 1]->getLocation()->x;

            }

            // Destination position is equal, sort start position in reading order
            if ($a[0]->getLocation()->y !== $b[0]->getLocation()->y) {
                // top down..
                return $a[0]->getLocation()->y - $b[0]->getLocation()->y;
            }

            // left to right
            return $a[0]->getLocation()->x - $b[0]->getLocation()->x;

        });
    }
}