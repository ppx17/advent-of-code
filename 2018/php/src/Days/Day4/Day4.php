<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 14:15
 */

namespace Ppx17\Aoc2018\Days\Day4;


use Ppx17\Aoc2018\Days\Day;

class Day4 extends Day
{
    private $events;
    private $guards;

    public function __construct(string $data)
    {
        parent::__construct($data);

        $this->events = explode("\n", trim($data));
        sort($this->events);
    }

    public function part1(): string
    {
        $this->guards = [];
        $currentGuard = null;
        $startMinute = null;

        foreach ($this->events as $event) {
            if (preg_match('/Guard #(?<id>[0-9]+) begins shift$/', $event, $matches) !== 0) {
                $currentGuard = $matches['id'];
                if (!isset($this->guards[$currentGuard])) {
                    $this->guards[$currentGuard] = [
                        'id' => $currentGuard,
                        'totalMinutes' => 0,
                        'minutes' => [],
                    ];
                }
            } elseif (preg_match('/:(?<min>[0-9]+)\] falls asleep/', $event, $matches) !== 0) {
                $startMinute = $matches['min'];
            } elseif (preg_match('/:(?<min>[0-9]+)\] wakes up/', $event, $matches) !== 0) {
                $stopMinute = $matches['min'];
                $this->guards[$currentGuard]['totalMinutes'] += $stopMinute - $startMinute;

                for ($i = $startMinute; $i < $stopMinute; $i++) {
                    $this->guards[$currentGuard]['minutes'][$i]++ ?? ($this->guards[$currentGuard]['minutes'][$i] = 1);
                }
            }
        }

        usort($this->guards, function ($a, $b) {
            return $b['totalMinutes'] - $a['totalMinutes'];
        });

        $mostAsleepOnMinute = array_search(max($this->guards[0]['minutes']), $this->guards[0]['minutes']);

        return (string)($this->guards[0]['id'] * $mostAsleepOnMinute);
    }

    public function part2(): string
    {
        $mostSleptOnMinute = 0;
        $globalSleepingRecord = null;
        $guardWithRecord = null;
        foreach ($this->guards as $guard) {
            if (count($guard['minutes']) === 0) {
                continue;
            }
            $personalSleepingRecord = max($guard['minutes']);
            if ($personalSleepingRecord > $mostSleptOnMinute) {
                $mostSleptOnMinute = $personalSleepingRecord;
                $globalSleepingRecord = array_search($personalSleepingRecord, $guard['minutes']);
                $guardWithRecord = $guard['id'];
            }
        }
        return (string)($guardWithRecord * $globalSleepingRecord);
    }
}