<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day13;

use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;
use Ppx17\Aoc2019\Aoc\Days\Day11\Map;

class Arcade
{
    public Map $map;
    public Vector $bat;
    public Vector $ball;
    public int $score;
    public bool $display = false;
    private IntCode $computer;
    private Vector $location;

    public array $tileMap = [
        0 => ' ',
        1 => '#',
        2 => '%',
        3 => '-',
        4 => 'O',
    ];
    private array $joystickMap = [
        0 => '|',
        1 => '/',
        -1 => '\\',
    ];

    private int $outputCount = 0;
    private int $tilesToBreak = 0;

    public function __construct(array $computerCode)
    {
        $this->computer = new IntCode($computerCode);
        $this->map = new Map(' ');
    }

    public function run(int $coins = null)
    {
        if ($coins !== null) {
            $this->computer->memory[0] = $coins;
        }

        $this->location = new Vector(0, 0);

        $this->computer->outputCallable = function ($out) {
            $this->outputReceived($out);
        };
        $this->computer->run();
    }

    public function enableAutoPilot()
    {
        $this->computer->inputCallable = function () {
            if ($this->display) {
                echo((string)$this->map);
                echo "\n Score: " . $this->score .
                    " Tiles left: " . $this->tilesToBreak .
                    " Joystick: " . $this->joystickMap[$this->joystick()] .
                    "\n\n";
            }
            return $this->joystick();
        };
    }

    public function reset()
    {
        $this->computer->reset();
    }

    public function tilesToBreak(): int
    {
        return $this
            ->map
            ->countTiles($this->tileMap[2]);
    }

    private function joystick(): int
    {
        if (is_null($this->bat) || is_null($this->ball) || ($this->bat->x === $this->ball->x)) {
            return 0;
        }

        return ($this->bat->x < $this->ball->x) ? 1 : -1;
    }

    private function outputReceived($out)
    {
        $step = $this->outputCount % 3;
        if ($step === 0) {
            $this->location->x = $out;
        } elseif ($step === 1) {
            $this->location->y = $out;
        } elseif ($step === 2) {
            if ($this->location->x === -1 && $this->location->y === 0) {
                $this->score = $out;
                $this->tilesToBreak = $this->tilesToBreak();
                if ($this->tilesToBreak === 0) {
                    $this->computer->halt();
                }
            } else {
                $this->map->paint($this->location, $this->tileMap[$out]);
                if ($out === 3) {
                    $this->bat = clone $this->location;
                } elseif ($out === 4) {
                    $this->ball = clone $this->location;
                }
            }
        }
        $this->outputCount++;
    }
}
