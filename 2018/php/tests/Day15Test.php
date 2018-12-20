<?php

namespace aoc2018\tests\day15;

use Aoc2018\Day15\ElfDiedException;
use Aoc2018\Day15\Map;
use Aoc2018\Day15\Simulator;

require_once 'day15.php';

class Day15Test extends \PHPUnit\Framework\TestCase
{
    public function testBattle1()
    {
        $map = new Map($this->dataBattle1());
        $simulator = new Simulator($map);

        $this->assertEquals(27730, $simulator->simulate(false));
    }

    public function testElvesSurvive1()
    {
        $data = $this->dataBattle1();

        $map = new Map($data, 15);
        $simulator = new Simulator($map, true);

        $this->assertEquals(4988, $simulator->simulate(false));
    }

    public function testElvesDie1()
    {
        $data = $this->dataBattle1();

        $map = new Map($data, 14);
        $simulator = new Simulator($map, true);

        $this->expectException(ElfDiedException::class);

        $simulator->simulate(false);
    }

    public function testBattle2()
    {
        $map = new Map($this->dataBattle2());
        $simulator = new Simulator($map);

        $this->assertEquals(39514, $simulator->simulate(false));
    }

    public function testElvesSurvive2()
    {
        $data = $this->dataBattle2();

        $map = new Map($data, 4);
        $simulator = new Simulator($map, true);

        $this->assertEquals(31284, $simulator->simulate(false));
    }

    public function testElvesDie2()
    {
        $data = $this->dataBattle2();

        $map = new Map($data, 3);
        $simulator = new Simulator($map, true);

        $this->expectException(ElfDiedException::class);

        $simulator->simulate(false);
    }

    public function testBattle3()
    {
        $map = new Map($this->dataBattle3());
        $simulator = new Simulator($map);

        $this->assertEquals(27755, $simulator->simulate(false));
    }

    public function testElvesSurvive3()
    {
        $data = $this->dataBattle3();

        $map = new Map($data, 15);
        $simulator = new Simulator($map, true);

        $this->assertEquals(3478, $simulator->simulate(false));
    }

    public function testElvesDie3()
    {
        $data = $this->dataBattle3();

        $map = new Map($data, 14);
        $simulator = new Simulator($map, true);

        $this->expectException(ElfDiedException::class);

        $simulator->simulate(false);
    }

    public function testBattle4()
    {
        $map = new Map($this->dataBattle4());
        $simulator = new Simulator($map);

        $this->assertEquals(28944, $simulator->simulate(false));
    }

    public function testElvesSurvive4()
    {
        $data = $this->dataBattle4();

        $map = new Map($data, 12);
        $simulator = new Simulator($map, true);

        $this->assertEquals(6474, $simulator->simulate(false));
    }

    public function testElvesDie4()
    {
        $data = $this->dataBattle4();

        $map = new Map($data, 11);
        $simulator = new Simulator($map, true);

        $this->expectException(ElfDiedException::class);

        $simulator->simulate(false);
    }

    public function testBattle5()
    {
        $map = new Map($this->dataBattle5());
        $simulator = new Simulator($map);

        $this->assertEquals(18740, $simulator->simulate(false));
    }

    public function testElvesSurvive5()
    {
        $data = $this->dataBattle5();

        $map = new Map($data, 34);
        $simulator = new Simulator($map, true);

        $this->assertEquals(1140, $simulator->simulate(false));
    }

    public function testElvesDie5()
    {
        $data = $this->dataBattle5();

        $map = new Map($data, 33);
        $simulator = new Simulator($map, true);

        $this->expectException(ElfDiedException::class);

        $simulator->simulate(false);
    }

    public function testBattleProduction()
    {
        $map = new Map($this->dataProduction());
        $simulator = new Simulator($map);

        $this->assertEquals(222831, $simulator->simulate(false));
    }

    public function testElvesSurviveProduction()
    {
        $data = $this->dataProduction();

        $map = new Map($data, 20);
        $simulator = new Simulator($map, true);

        $result = $simulator->simulate(false);
        $this->assertNotEquals(55019, $result);
        $this->assertLessThan(57800, $result);
    }

    public function testElvesDieProduction()
    {
        $data = $this->dataProduction();

        $map = new Map($data, 19);
        $simulator = new Simulator($map, true);

        $this->expectException(ElfDiedException::class);

        $simulator->simulate(false);
    }

    private function dataBattle1(): string
    {
        return "#######
#.G...#
#...EG#
#.#.#G#
#..G#E#
#.....#
#######";
    }

    private function dataBattle2(): string
    {
        return "#######
#E..EG#
#.#G.E#
#E.##E#
#G..#.#
#..E#.#
#######";
    }

    private function dataBattle3(): string
    {
        return "#######
#E.G#.#
#.#G..#
#G.#.G#
#G..#.#
#...E.#
#######";
    }

    private function dataBattle4(): string
    {
        return "#######
#.E...#
#.#..G#
#.###.#
#E#G#G#
#...#G#
#######";
    }

    private function dataBattle5(): string
    {
        return "#########
#G......#
#.E.#...#
#..##..G#
#...##..#
#...#...#
#.G...G.#
#.....G.#
#########";
    }

    private function dataProduction(): string
    {
        return file_get_contents('../input/input-day15.txt');
    }

}