<?php

namespace Ppx17\Aoc2018\Tests;


use PHPUnit\Framework\TestCase;
use Ppx17\Aoc2018\Days\Day24\Simulator;
use Ppx17\Aoc2018\Days\Day24\SimulatorFactory;

class Day24Test extends TestCase
{
    private $data = "Immune System:
17 units each with 5390 hit points (weak to radiation, bludgeoning) with an attack that does 4507 fire damage at initiative 2
989 units each with 1274 hit points (immune to fire; weak to bludgeoning, slashing) with an attack that does 25 slashing damage at initiative 3

Infection:
801 units each with 4706 hit points (weak to radiation) with an attack that does 116 bludgeoning damage at initiative 1
4485 units each with 2961 hit points (immune to radiation; weak to fire, cold) with an attack that does 12 slashing damage at initiative 4";

    public function testPart1()
    {
        /** @var Simulator $simulator */
        $simulator = (new SimulatorFactory())->create($this->data);
        $this->assertEquals(5216, $simulator->fightToDeath());
    }

    public function testPart2()
    {
        /** @var Simulator $simulator */
        $simulator = (new SimulatorFactory())->create($this->data, 1570);
        $this->assertEquals(51, $simulator->fightToDeath());
    }
}