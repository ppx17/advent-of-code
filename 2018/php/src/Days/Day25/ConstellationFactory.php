<?php

namespace Ppx17\Aoc2018\Days\Day25;


class ConstellationFactory
{
    public function create(array $lines): array {
        $constellations = [];
        $points = [];
        foreach($lines as $line) {
            if(empty($line)) { continue; }
            $points[] = new VectorX(explode(',', $line));
        }

        $constellation = null;
        $removed = false;
        while(count($points) > 0) {
            if ($constellation === null || $removed === false) {
                $constellation = new Constellation();
                $constellations[] = $constellation;
            }
            $removed = false;

            foreach ($points as $index => $point) {
                if ($constellation->fitsIn($point)) {
                    $constellation->add($point);
                    unset($points[$index]);
                    $removed = true;
                }
            }
        }

        return $constellations;
    }
}