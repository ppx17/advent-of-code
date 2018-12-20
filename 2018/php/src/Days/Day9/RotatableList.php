<?php

namespace Ppx17\Aoc2018\Days\Day9;


use SplDoublyLinkedList;

class RotatableList extends SplDoublyLinkedList
{
    function rotate($steps)
    {
        if ($steps > 0) {
            for ($i = 0; $i < $steps; $i++) {
                $this->unshift($this->pop());
            }
        } else {
            for ($i = 0; $i > $steps; $i--) {
                $this->push($this->shift());
            }
        }
    }
}