<?php

namespace Ppx17\Aoc2019\Aoc\Days;

use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;
use Ppx17\Aoc2019\Aoc\Days\Day13\IntCode;

class Day19 extends AbstractDay
{
    private IntCode $computer;

    public function dayNumber(): int
    {
        return 19;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->computer = new IntCode($this->getInputIntCode());
    }

    public function part1(): string
    {
        $beamTiles = 0;
        $vector = new Vector();
        for ($vector->x = 0; $vector->x < 50; $vector->x++) {
            for ($vector->y = 0; $vector->y < 50; $vector->y++) {
                if ($this->inBeam($vector)) {
                    $beamTiles++;
                }
            }
        }
        return (string)$beamTiles;
    }

    public function part2(): string
    {
        $topRightOffset = new Vector(99, -99);
        $bottomLeft = new Vector(250, 500);

        while (!$this->inBeam($bottomLeft)) {
            // Move sideways towards the beam
            $bottomLeft->x++;
        }

        // Check from bottom-left corner if the rest fits on top
        while (!$this->inBeam($bottomLeft->add($topRightOffset))) {
            // Drop lower if it doesn't fit
            $bottomLeft->y++;

            // Move sideways towards the beam again
            while (!$this->inBeam($bottomLeft)) {
                $bottomLeft->x++;
            }
        }

        // We are now the bottom-left corner of a fitting box, don't forget that the answer requires the top-left corner.
        return (string)(($bottomLeft->x * 10000) + ($bottomLeft->y - 99));
    }

    private function inBeam(Vector $position): bool
    {
        $this->computer->reset();
        $this->computer->inputList[] = $position->x;
        $this->computer->inputList[] = $position->y;
        $this->computer->run();

        return $this->computer->output === 1;
    }
}
