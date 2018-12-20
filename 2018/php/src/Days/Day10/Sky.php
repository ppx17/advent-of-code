<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 15:22
 */

namespace Ppx17\Aoc2018\Days\Day10;


class Sky
{
    private $positionsX = [];
    private $positionsY = [];
    private $velocitiesX = [];
    private $velocitiesY = [];
    private $lights = 0;
    private $stepsTaken = 0;

    public function addLight(int $posX, int $posY, int $velX, int $velY): void
    {
        $this->positionsX[] = $posX;
        $this->positionsY[] = $posY;
        $this->velocitiesX[] = $velX;
        $this->velocitiesY[] = $velY;
        $this->lights++;
    }

    public function move(int $steps = 1): void
    {
        for ($i = 0; $i < $this->lights; $i++) {
            $this->positionsX[$i] += ($this->velocitiesX[$i] * $steps);
            $this->positionsY[$i] += ($this->velocitiesY[$i] * $steps);
        }
        $this->stepsTaken += $steps;
    }

    public function height(): int
    {
        return max($this->positionsY) - min($this->positionsY);
    }

    public function print(): string
    {
        $sky = [];
        for ($y = min($this->positionsY); $y <= max($this->positionsY); $y++) {
            for ($x = min($this->positionsX); $x <= max($this->positionsX); $x++) {
                $sky[$y][$x] = ".";
            }
        }
        for($i=0;$i<$this->lights;$i++) {
            $sky[$this->positionsY[$i]][$this->positionsX[$i]] = '#';
        }

        return implode(PHP_EOL, array_map(function ($row) {
                return implode("", $row);
            }, $sky));
    }

    public function getStepsTaken(): int
    {
        return $this->stepsTaken;
    }
}
