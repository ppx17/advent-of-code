<?php

namespace aoc2018\tests\day22;


use PHPUnit\Framework\TestCase;
use Ppx17\Aoc2018\Days\Common\AStar\AStar;
use Ppx17\Aoc2018\Days\Common\Vector;
use Ppx17\Aoc2018\Days\Day22\Map;
use Ppx17\Aoc2018\Days\Day22\MapNodeGenerator;
use Ppx17\Aoc2018\Days\Day22\Node;

class Day22Test extends TestCase
{
    public function testType()
    {
        $map = $this->getTestMap();

        $this->assertEquals(0, $map->getType(0, 0));
        $this->assertEquals(1, $map->getType(1, 0));
        $this->assertEquals(0, $map->getType(0, 1));
        $this->assertEquals(2, $map->getType(1, 1));
        $this->assertEquals(0, $map->getType(10, 10));
    }

    public function testRiskLevel()
    {
        $map = $this->getTestMap();

        $this->assertEquals(114, $map->riskLevel());
    }

    public function testPath() {
        $map = $this->getTestMap();

        $aStar = new AStar(new MapNodeGenerator($map));

        $start = new Node(new Vector(0, 0), 'T');
        $dest = new Node(new Vector(10, 10), 'T');

        $path = $aStar->run($start, $dest);

        $finalNode = last($path);


        $this->assertEquals(45, $finalNode->getG());
    }

    /**
     * @return Map
     */
    private function getTestMap(): Map
    {
        $map = new Map(new Vector(10, 10), 510);
        return $map;
    }
}