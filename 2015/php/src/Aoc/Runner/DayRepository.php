<?php

namespace Ppx17\Aoc2015\Aoc\Runner;


use Illuminate\Support\Collection;
use IteratorAggregate;

/**
 * Class DayRepository
 * @package Ppx17\Aoc2015\Aoc\Runner
 *
 * @method each(callable $callback);
 * @method map(callable $callback);
 */
class DayRepository implements IteratorAggregate
{
    private Collection $days;

    public function __construct()
    {
        $this->days = new Collection();
    }

    public function addDay(DayInterface $day): self
    {
        $this->days->put($day->dayNumber(), $day);
        return $this;
    }

    public function getDay(int $dayNumber): ?DayInterface
    {
        return $this->days->get($dayNumber);
    }

    public function getIterator()
    {
        return $this->days->sortKeys()->getIterator();
    }

    public function __call($name, $arguments)
    {
        if(method_exists($this->days, $name)) {
            return $this->days->{$name}(...$arguments);
        }
    }
}