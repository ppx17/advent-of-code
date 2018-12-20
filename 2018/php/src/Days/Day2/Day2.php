<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 13:55
 */

namespace Ppx17\Aoc2018\Days\Day2;


use Ppx17\Aoc2018\Days\Day;

class Day2 extends Day
{
    private $ids;

    public function __construct(string $data)
    {
        parent::__construct($data);

        $this->ids = explode("\n", trim($data));
    }

    public function part1(): string
    {
        $twos = $threes = 0;
        foreach ($this->ids as $id) {
            $counts = [];
            for ($i = 0; $i < strlen($id); $i++) {
                $counts[$id[$i]]++;
            }
            if (in_array(2, $counts)) {
                $twos++;
            }
            if (in_array(3, $counts)) {
                $threes++;
            }
        }

        return (string)($twos * $threes);
    }

    public function part2(): string
    {
        for ($fi = 0; $fi < count($this->ids); $fi++) {
            for ($si = $fi + 1; $si < count($this->ids); $si++) {
                if (levenshtein($this->ids[$fi], $this->ids[$si]) === 1) {
                    return $this->common($this->ids[$fi], $this->ids[$si]);
                }
            }
        }
        return '';
    }

    private function common($first, $second): string
    {
        $result = '';
        for ($i = 0; $i < strlen($first); $i++) {
            $result .= ($first[$i] === $second[$i]) ? $first[$i] : '';
        }
        return $result;
    }
}