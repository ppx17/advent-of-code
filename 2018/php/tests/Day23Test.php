<?php

namespace Ppx17\Aoc2018\Tests;

use PHPUnit\Framework\TestCase;
use Ppx17\Aoc2018\Days\Day23\Day23;

class Day23Test extends TestCase
{
    public function testPart1()
    {
        $day = new Day23($this->dataPart1());
        $this->assertEquals(7, $day->part1());
    }

    public function testPart2() {

        $day = new Day23($this->dataPart2());
        $this->assertEquals(36, $day->part2());
    }

    public function dataPart1()
    {
        return "pos=<0,0,0>, r=4
pos=<1,0,0>, r=1
pos=<4,0,0>, r=3
pos=<0,2,0>, r=1
pos=<0,5,0>, r=3
pos=<0,0,3>, r=1
pos=<1,1,1>, r=1
pos=<1,1,2>, r=1
pos=<1,3,1>, r=1";
    }

    public function dataPart2()
    {
        return "pos=<10,12,12>, r=2
pos=<12,14,12>, r=2
pos=<16,12,12>, r=4
pos=<14,14,14>, r=6
pos=<50,50,50>, r=200
pos=<10,10,10>, r=5";
    }
}
