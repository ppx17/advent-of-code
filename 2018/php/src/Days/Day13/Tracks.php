<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 16:16
 */

namespace Ppx17\Aoc2018\Days\Day13;


use Ppx17\Aoc2018\Days\Common\Vector;

class Tracks
{
    private $tracks;

    public function __construct($tracks)
    {
        $this->tracks = $tracks;
    }

    public function at(Vector $location): string
    {
        return $this->tracks[$location->y][$location->x];
    }
}