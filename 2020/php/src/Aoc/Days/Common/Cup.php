<?php


namespace Ppx17\Aoc2020\Aoc\Days\Common;


class Cup
{
    public int $label;
    public ?Cup $next;

    public function __construct(int $label)
    {
        $this->label = $label;
        $this->next = null;
    }
}