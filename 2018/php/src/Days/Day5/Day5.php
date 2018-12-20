<?php

namespace Ppx17\Aoc2018\Days\Day5;


use Ppx17\Aoc2018\Days\Day;

class Day5 extends Day
{
    private $part1;

    public function __construct(string $data)
    {
        parent::__construct(trim($data));
    }

    public function part1(): string
    {
        $this->part1 = $this->reactStrReplace($this->data);
        return (string)strlen($this->part1);
    }

    public function part2(): string
    {
        $this->part1 ?? $this->reactStrReplace($this->data);

        $smallest = strlen($this->part1);

        $sum = 0;
        $count = 0;

        for ($x = ord('a'); $x <= ord('z'); $x++) {
            $letter = chr($x);

            $reacted = strlen($this->reactStrReplace($this->part1, $letter));

            $sum += $reacted;
            $count++;

            if ($reacted < $smallest) {
                $smallest = $reacted;
            }

            if ($reacted < ($sum / $count) * 0.8) {
                // This one is more than 20% smaller, which qualifies as 'significantly' as stated in the assignment, so we cancel out here.
                break;
            }
        }

        return (string)$smallest;
    }

    private function reactStrReplace(string $units, ?string $exclude = null): string
    {
        if ($exclude) {
            $units = str_replace([$exclude, strtoupper($exclude)], '', $units);
        }

        $len = strlen($units);
        $i = 0;

        while ($i < $len - 1) {
            if (abs(ord($units[$i]) - ord($units[$i + 1])) === 32) {
                $units = substr($units, 0, $i) . substr($units, $i + 2);
                if ($i) {
                    $i--;
                }
                $len -= 2;
            } else {
                $i++;
            }
        }

        return $units;
    }
}