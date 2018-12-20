<?php

namespace Ppx17\Aoc2018\Days\Day13;


use Ppx17\Aoc2018\Days\Common\Vector;
use Ppx17\Aoc2018\Days\Day;

class Day13 extends Day
{
    private $simulator;

    public function __construct(string $data)
    {
        parent::__construct($data);
        $this->simulator = $this->buildSimulator();
        $this->simulator->run();
    }

    public function part1(): string
    {
        $location = $this->simulator->getFirstImpactLocation();
        return sprintf("%s,%s", $location->x, $location->y);
    }

    public function part2(): string
    {
        $cart = $this->simulator->getLastCartStanding();
        return sprintf("%s,%s", $cart->location->x, $cart->location->y);
    }

    private function buildSimulator(): Simulator
    {
        $lines = explode("\n", $this->data);
        $tracksArray = [];
        $carts = [];
        $cartCount = 0;
        for ($y = 0; $y < count($lines); $y++) {
            $tracksArray[$y] = str_split($lines[$y], 1);
            for ($x = 0; $x < count($tracksArray[$y]); $x++) {
                $direction = null;
                switch ($tracksArray[$y][$x]) {
                    case '>':
                        $tracksArray[$y][$x] = '-';
                        $direction = new Vector(1, 0);
                        break;
                    case '<':
                        $tracksArray[$y][$x] = '-';
                        $direction = new Vector(-1, 0);
                        break;
                    case '^':
                        $tracksArray[$y][$x] = '|';
                        $direction = new Vector(0, -1);
                        break;
                    case 'v':
                        $tracksArray[$y][$x] = '|';
                        $direction = new Vector(0, 1);
                        break;
                }
                if ($direction !== null) {
                    $carts[] = new Cart($cartCount++, new Vector($x, $y), $direction);
                }
            }
        }

        $tracks = new Tracks($tracksArray);
        return new Simulator($carts, $tracks);
    }
}