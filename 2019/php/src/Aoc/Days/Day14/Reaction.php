<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day14;

use Illuminate\Support\Collection;

class Reaction
{
    public string $result;
    public int $amount;

    public Collection $ingredients;

    public function __construct()
    {
        $this->ingredients = new Collection();
    }
}
