<?php
namespace Ppx17\Aoc2018;

class Timers
{
    private $initialSnapshot;
    private $lastSnapshot;

    private $measurements = [];

    public function __construct()
    {
        $this->initialSnapshot = $this->lastSnapshot = $this->snapshot();
    }

    public function measure(string $name)
    {
        $now = $this->snapshot();
        $this->measurements[$name] = [
            'ms' => round(($now['ms'] - $this->lastSnapshot['ms']) * 1000, 3),
            'mem' => $now['mem'] - $this->lastSnapshot['mem'],
        ];
        $this->lastSnapshot = $now;
    }

    public function results(): array
    {
        $now = $this->snapshot();
        $this->measurements['_total'] = [
            'ms' => round(($now['ms'] - $this->initialSnapshot['ms']) * 1000, 3),
            'mem' => $now['mem'] - $this->initialSnapshot['mem'],
        ];

        return $this->measurements;
    }

    private function snapshot()
    {
        return [
            'ms' => microtime(true),
            'mem' => memory_get_usage()
        ];
    }
}