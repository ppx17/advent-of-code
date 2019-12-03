<?php


namespace Ppx17\Aoc2019\Aoc\Days;


class Day1 extends AbstractDay
{
    public function dayNumber(): int
    {
        return 1;
    }

    public function part1(): string
    {
        return (string)array_sum(array_map([$this, 'fuelForMass'], $this->getInputLines()));
    }

    public function part2(): string
    {
        return (string)array_sum(array_map([ $this, 'fuelForMassAndFuel'], $this->getInputLines()));
    }

    private function fuelForMass(int $mass): int
    {
        return floor($mass / 3) - 2;
    }

    private function fuelForMassAndFuel(int $mass)
    {
        $totalFuel = $lastFuel = $this->fuelForMass($mass);
        do {
            $lastFuel = $this->fuelForMass($lastFuel);
            $totalFuel += ($lastFuel > 0) ? $lastFuel : 0;
        } while ($lastFuel > 0);
        return $totalFuel;
    }
}
