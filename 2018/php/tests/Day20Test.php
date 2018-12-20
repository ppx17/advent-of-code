<?php

namespace aoc2018\tests\day20;

use aoc2018\day20\Solver;

require_once 'day20.php';

class Day20Test extends \PHPUnit\Framework\TestCase
{
    public function testPart1Sample1()
    {
        $sample = '^WNE$';

        $result = Solver::part1($sample);

        $this->assertEquals(3, $result);
    }

    public function testPart1Sample2()
    {
        $sample = '^ENWWW(NEEE|SSE(EE|N))$';

        $result = Solver::part1($sample);

        $this->assertEquals(10, $result);
    }

    public function testPart1Sample3()
    {
        $sample = '^ENNWSWW(NEWS|)SSSEEN(WNSE|)EE(SWEN|)NNN$';

        $result = Solver::part1($sample);


        $this->assertEquals(18, $result);
    }

    public function testPart1Sample4()
    {
        $sample = '^ESSWWN(E|NNENN(EESS(WNSE|)SSS|WWWSSSSE(SW|NNNE)))$';

        $result = Solver::part1($sample);

        $this->assertEquals(23, $result);
    }
}