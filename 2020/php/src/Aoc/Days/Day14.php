<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Closure;

class Day14 extends AbstractDay
{
    private const INT_SIZE = 36;
    private array $data;

    public function dayNumber(): int
    {
        return 14;
    }

    public function setUp(): void
    {
        $this->data = array_map(fn($x) => explode(' = ', $x), $this->getInputLines());
    }

    public function part1(): string
    {
        $mem = $this->processLines(function ($value, $mask, $addr, &$mem) {
            $mZeroes = bindec(str_replace(['X', '0', '1'], ['1', '0', '1'], $mask));
            $mOnes = bindec(str_replace('X', '0', $mask));

            $mem[$addr] = $value & $mZeroes | $mOnes;
        });

        return array_sum($mem);
    }

    public function part2(): string
    {
        $mem = $this->processLines(function ($value, $mask, $addr, &$mem) {
            $binAddr = $this->toBinaryString($addr);

            $addresses = [''];

            for ($i = 0; $i < self::INT_SIZE; $i++) {
                switch ($mask[$i]) {
                    case 'X':
                        foreach ($addresses as $k => $v) {
                            $addresses[] = $addresses[$k] . '1';
                            $addresses[$k] .= '0';
                        }
                        break;
                    case '0':
                        foreach ($addresses as $k => $v) {
                            $addresses[$k] .= $binAddr[$i];
                        }
                        break;
                    case '1':
                        foreach ($addresses as $k => $v) {
                            $addresses[$k] .= '1';
                        }
                        break;
                }
            }

            foreach ($addresses as $v) {
                $mem[bindec($v)] = (int)$value;
            }
        });

        return array_sum($mem);
    }

    private function toBinaryString($number): string
    {
        return str_pad(sprintf('%b', (int)$number), self::INT_SIZE, '0', STR_PAD_LEFT);
    }

    private function processLines(Closure $process): array
    {
        $mem = [];
        $mask = '';

        foreach ($this->data as $line) {
            if ($line[0] === 'mask') {
                $mask = $line[1];
                continue;
            }

            preg_match('#mem\[(\d+)]#', $line[0], $m);
            $addr = $m[1];

            $process($line[1], $mask, $addr, $mem);
        }

        return $mem;
    }
}