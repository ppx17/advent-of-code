<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Illuminate\Support\Collection;

class Day13 extends AbstractDay
{
    private int $earliest;
    private Collection $schema;

    public function dayNumber(): int
    {
        return 13;
    }

    public function setUp(): void
    {
        $lines = $this->getInputLines();
        $this->earliest = (int)$lines[0];
        $this->schema = collect(explode(',', $lines[1]))
            ->reject(fn($x) => $x === 'x')
            ->map(fn($x) => (int)$x);
    }

    public function part1(): string
    {
        return array_product($this->schema
            ->map(fn($b) => [$b - ($this->earliest % $b), $b])
            ->pipe(fn($x) => $x->first(fn($y) => $y[0] === $x->min(0))));
    }

    public function part2(): string
    {
        return $this->chineseRemainder(
            $this->schema->values()->toArray(),
            $this->schema->flip()->map(fn($x) => -$x)->values()->toArray()
        );
    }

    private function chineseRemainder(array $n, array $a): int
    {
        $prod = array_product($n);
        for ($sum = 0, $i = 0; $i < count($n); $i++) {
            $p = $prod / $n[$i];
            $sum += $a[$i] * $this->invMod($p, $n[$i]) * $p;
        }
        $res = $sum % $prod;
        if ($res < 0) $res += $prod;
        return $res;
    }

    // https://rosettacode.org/wiki/Modular_inverse#PHP
    private function invMod($a, $n): int
    {
        if ($n < 0) $n = -$n;
        if ($a < 0) $a = $n - (-$a % $n);
        $t = 0;
        $nt = 1;
        $r = $n;
        $nr = $a % $n;
        while ($nr != 0) {
            $quot = intval($r / $nr);
            $tmp = $nt;
            $nt = $t - $quot * $nt;
            $t = $tmp;
            $tmp = $nr;
            $nr = $r - $quot * $nr;
            $r = $tmp;
        }
        if ($r > 1) return -1;
        if ($t < 0) $t += $n;
        return $t;
    }
}
