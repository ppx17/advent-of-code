<?php

namespace Ppx17\Aoc2020\Aoc\Days;

use Ppx17\Aoc2020\Aoc\Days\Common\Cup;

class Day23 extends AbstractDay
{
    private array $cups;

    public function dayNumber(): int
    {
        return 23;
    }

    public function setUp(): void
    {
        ini_set('memory_limit', '256M');
        $this->cups = array_map(fn($x) => (int)$x, str_split($this->getInputTrimmed()));
    }

    public function part1(): string
    {
        $cup = $this->play($this->cups, 100);

        $result = '';
        for ($i = 0; $i < 8; $i++) {
            $cup = $cup->next;
            $result .= $cup->label;
        }

        return $result;
    }

    public function part2(): string
    {
        $cups = array_merge($this->cups, range(max($this->cups) + 1, 1_000_000));
        $firstCup = $this->play($cups, 10_000_000);

        return $firstCup->next->label * $firstCup->next->next->label;
    }

    private function play(array $labels, int $rounds): Cup
    {
        $count = count($labels);
        $cups = array_map(fn($x) => new Cup($x), $labels);
        $cupsByLabel = [];
        foreach ($cups as $index => $cup) {
            $ni = $index + 1;
            if ($ni === $count) {
                $ni = 0;
            }
            $cup->next = $cups[$ni];
            $cupsByLabel[$cup->label] = $cup;
        }

        $current = $cups[0];
        for ($i = 0; $i < $rounds; $i++) {
            // Take the three following cups
            $one = $current->next;
            $two = $one->next;
            $three = $two->next;

            $afterTaken = $three->next;
            $current->next = $afterTaken;

            $destLabel = $current->label;
            do {
                $destLabel = ($destLabel > 1) ? $destLabel - 1 : $count;
            } while ($destLabel === $one->label || $destLabel === $two->label || $destLabel === $three->label);

            $destination = $cupsByLabel[$destLabel];
            $oldNext = $destination->next;

            // Link the taken sequence back in
            $destination->next = $one;
            $three->next = $oldNext;

            // And move to the next cup
            $current = $current->next;
        }

        return $cupsByLabel[1];
    }

    /**
     * Runtime for part two would have been around 6,5 days.
     *
     * @return string
     */
    private function part1Original(): string
    {

        $cups = $this->cups;
        $max = max($this->cups);
        for ($i = 0; $i < 100; $i++) {
            $pickedUp = array_slice($cups, 1, 3);
            $dest = $cups[0];
            do {
                $dest--;
                if ($dest === 0) {
                    $dest = $max;
                }
            } while (in_array($dest, $pickedUp));
            $new = array_merge([$cups[0]], array_slice($cups, 4));
            $destinationOffset = array_search($dest, $new) + 1;
            $new = array_merge(array_slice($new, 0, $destinationOffset), $pickedUp, array_slice($new, $destinationOffset));
            $cups = array_merge(array_slice($new, 1), [$new[0]]);
        }
        $start = array_search(1, $cups);
        $result = '';
        $count = count($this->cups);
        for ($i = 1; $i < $count; $i++) {
            $result .= $cups[($start + $i) % $count];
        }

        return $result;
    }
}

