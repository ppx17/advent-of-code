<?php

namespace Ppx17\Aoc2018\Days\Day3;


use Ppx17\Aoc2018\Days\Day;

class Day3 extends Day
{
    private $claims = [];
    private $grid = [];

    public function __construct(string $data)
    {
        parent::__construct($data);

        preg_match_all(
            "/#(?<id>[0-9]+) @ (?<x>[0-9]+),(?<y>[0-9]+): (?<w>[0-9]+)x(?<h>[0-9]+)/",
            $data,
            $this->claims,
            PREG_SET_ORDER);
    }

    public function part1(): string
    {
        $this->claimsToInt();

        $this->placeClaimsOnGrid();

        $count = 0;

        foreach ($this->grid as $value) {
            if ($value === true) {
                $count++;
            }
        }

        return (string)$count;
    }

    public function part2(): string
    {
        foreach ($this->claims as $claim) {
            if (!$this->hasOverlap($claim)) {
                return $claim['id'];
            }
        }
    }

    private function hasOverlap($claim)
    {
        for ($x = $claim['x']; $x < ($claim['x'] + $claim['w']); $x++) {
            for ($y = $claim['y']; $y < ($claim['y'] + $claim['h']); $y++) {
                $idx = $x * 1000 + $y;
                if ($this->grid[$idx] === true) {
                    return true;
                }
            }
        }
        return false;
    }

    private function claimsToInt(): void
    {
        for ($i = 0; $i < count($this->claims); $i++) {
            $this->claims[$i]['x'] = (int)$this->claims[$i]['x'];
            $this->claims[$i]['y'] = (int)$this->claims[$i]['y'];
            $this->claims[$i]['w'] = (int)$this->claims[$i]['w'];
            $this->claims[$i]['h'] = (int)$this->claims[$i]['h'];
        }
    }

    private function placeClaimsOnGrid(): void
    {
        $this->grid = [];

        foreach ($this->claims as $claim) {
            $this->placeClaimOnGrid($claim);
        }
    }

    private function placeClaimOnGrid(array $claim): void
    {
        $maxX = ($claim['x'] + $claim['w']);
        $maxY = ($claim['y'] + $claim['h']);
        $startY = $claim['y'];
        for ($x = $claim['x']; $x < $maxX; $x++) {
            for ($y = $startY; $y < $maxY; $y++) {
                $idx = $x * 1000 + $y;
                if (isset($this->grid[$idx])) {
                    $this->grid[$idx] = true;
                } else {
                    $this->grid[$idx] = false;
                }
            }
        }
    }
}