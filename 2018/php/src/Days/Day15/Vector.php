<?php

namespace Ppx17\Aoc2018\Days\Day15;


class Vector extends \Ppx17\Aoc2018\Days\Common\Vector
{
    /**
     * Get neighbors in reading order (top, left, right, bottom)
     * @return array
     */
    public function neighbors(): array
    {
        $neighbors = [];
        if ($this->y > 1) {
            $neighbors[] = new Vector($this->x, $this->y - 1);
        }
        if ($this->x > 1) {
            $neighbors[] = new Vector($this->x - 1, $this->y);
        }
        $neighbors[] = new Vector($this->x + 1, $this->y);
        $neighbors[] = new Vector($this->x, $this->y + 1);

        return $neighbors;
    }
}