<?php

namespace Ppx17\Aoc2018\Tests;


use PHPUnit\Framework\TestCase;
use Ppx17\Aoc2018\Days\Day25\ConstellationFactory;
use Ppx17\Aoc2018\Days\Day25\VectorX;

class Day25Test extends TestCase
{
    public function testVectorX() {
        $a = new VectorX([0,0,3,0]);
        $b = new VectorX([1,3,0,1]);

        $this->assertEquals(8, $a->manhattanDistance($b));
    }

    public function testConstellationFactory()
    {
        $factory = new ConstellationFactory();

        $this->assertCount(4, $factory->create(explode("\n", $this->data1)));
        $this->assertCount(3, $factory->create(explode("\n", $this->data2)));
        $this->assertCount(8, $factory->create(explode("\n", $this->data3)));
    }

    private $data1 = "-1,2,2,0
0,0,2,-2
0,0,0,-2
-1,2,0,0
-2,-2,-2,2
3,0,2,-1
-1,3,2,2
-1,0,-1,0
0,2,1,-2
3,0,0,0";

    private $data2 = "1,-1,0,1
2,0,-1,0
3,2,-1,0
0,0,3,1
0,0,-1,-1
2,3,-2,0
-2,2,0,0
2,-2,0,-1
1,-1,0,-1
3,2,0,2";

    private $data3="1,-1,-1,-2
-2,-2,0,1
0,2,1,3
-2,3,-2,1
0,2,3,-2
-1,-1,1,-2
0,-2,-1,0
-2,2,3,-1
1,2,2,0
-1,-2,0,-2";
}