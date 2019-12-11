<?php


namespace Ppx17\Aoc2015\Aoc\Days;


use Illuminate\Support\Collection;
use Ppx17\Aoc2015\Aoc\Days\Day7\AndPromise;
use Ppx17\Aoc2015\Aoc\Days\Day7\AssignPromise;
use Ppx17\Aoc2015\Aoc\Days\Day7\LShiftPromise;
use Ppx17\Aoc2015\Aoc\Days\Day7\NotPromise;
use Ppx17\Aoc2015\Aoc\Days\Day7\OrPromise;
use Ppx17\Aoc2015\Aoc\Days\Day7\Promise;
use Ppx17\Aoc2015\Aoc\Days\Day7\RShiftPromise;

class Day7 extends AbstractDay
{
    private const MASK = 0xFFFF;
    private Collection $operations;
    private Collection $known;
    private string $part1;

    public function __construct()
    {
        $this->known = new Collection();
    }

    public function dayNumber(): int
    {
        return 7;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->operations = $this->match('#^(?<number>[0-9]+) -> (?<output>[a-z]+)$#m')
            ->mapWithKeys(fn($match) => [$match['output'] => (int)$match['number']])
            ->merge(
                $this->match('#^(?<input>[a-z]+) (?<op>LSHIFT|RSHIFT) (?<distance>[0-9]+) -> (?<output>[a-z]+)$#m')
                    ->mapWithKeys(function (array $op) {
                        $promise = ($op['op'] === 'LSHIFT' ? new LShiftPromise() : new RShiftPromise());
                        $promise->distance = (int)$op['distance'];
                        $promise->inputVariables = [$op['input']];
                        return [$op['output'] => $promise];
                    })
            )
            ->merge(
                $this->match('#^(?<a>[a-z|0-9]+) (?<op>AND|OR) (?<b>[a-z|0-9]+) -> (?<output>[a-z]+)$#m')
                    ->mapWithKeys(function (array $op) {
                        $promise = ($op['op'] === 'AND' ? new AndPromise() : new OrPromise());
                        $promise->inputVariables = [$op['a'], $op['b']];
                        return [$op['output'] => $promise];
                    })
            )
            ->merge(
                $this->match('#^NOT (?<input>[a-z]+) -> (?<output>[a-z]+)$#m')
                    ->mapWithKeys(function (array $op) {
                        $promise = new NotPromise();
                        $promise->inputVariables = [$op['input']];
                        return [$op['output'] => $promise];
                    })
            )
            ->merge(
                $this->match('#^(?<input>[a-z]+) -> (?<output>[a-z]+)$#m')
                    ->mapWithKeys(function (array $op) {
                        $promise = new AssignPromise();
                        $promise->inputVariables = [$op['input']];
                        return [$op['output'] => $promise];
                    })
            )
            ->sortKeys();
    }

    public function part1(): string
    {
        $this->part1 = (string)$this->resolve('a');
        return $this->part1;
    }

    public function part2(): string
    {
        $this->known = new Collection();
        $this->known->put('b', $this->part1);
        return (string)$this->resolve('a');
    }

    private function match(string $pattern): Collection
    {
        $matches = [];
        preg_match_all($pattern, $this->getInput(), $matches, PREG_SET_ORDER);
        return collect($matches);
    }

    private function resolve($varName): int
    {
        if (is_numeric($varName)) {
            return (int)$varName;
        }
        if (!isset($this->operations[$varName])) {
            throw new \RuntimeException('No operation for variable ' . $varName);
        }
        if($this->known->has($varName))
        {
            return $this->known->get($varName);
        }

        $variable = $this->operations[$varName];

        if(is_int($variable)) {
            $this->known->put($varName, $variable & self::MASK);
            return $this->known->get($varName);
        }

        if ($variable instanceof Promise) {
            $inputs = collect($variable->inputVariables)
                ->map(fn($var) => $this->resolve($var))
                ->toArray();
            $result = $variable->resolve($inputs) & self::MASK;
            $this->known->put($varName, $result);
            return $result;
        }
    }
}
