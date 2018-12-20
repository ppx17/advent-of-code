<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 13:29
 */

namespace Ppx17\Aoc2018\Days\Day17;


use Ppx17\Aoc2018\Days\Common\Vector;

class Simulator
{
    private $queue;
    private $map;
    private $onQueue = [];

    public function __construct(Map $map, Vector $start)
    {
        $this->map = $map;
        $this->queue = new \SplQueue();
        $this->queue($start);
    }

    public function simulate()
    {
        while (!$this->queue->isEmpty()) {
            $this->simulateStep();
        }
    }

    private function simulateStep()
    {
        $current = $this->dequeue();

        if ($this->map->isFree($current->down())) {
            $this->map->setRunningWater($current);
            $this->queue($current->down());
            return;
        }

        $right = $current;
        $boxedRight = true;
        do {
            $right = $right->right();
            if ($this->map->isFree($right->down())) {
                // We can start falling again
                $this->queue($right->down());
                $boxedRight = false;
                break;
            }
        } while ($this->map->isFree($right) && $boxedRight);

        $left = $current;
        $boxedLeft = true;
        do {
            $left = $left->left();
            if ($this->map->isFree($left->down())) {
                // We can start falling again
                $this->queue($left->down());
                $boxedLeft = false;
                break;
            }
        } while ($this->map->isFree($left) && $boxedLeft);

        if ($boxedLeft && $boxedRight) {
            $this->map->setBetween($left->right(), $right->left(), '~');
            $this->queue($current->up());
        } else {
            $this->map->setBetween(
                (($boxedLeft) ? $left->right() : $left),
                (($boxedRight) ? $right->left() : $right),
                '|');
        }
    }

    private function queue(Vector $step): void
    {
        $index = $step->y * $this->map->maxY + $step->x;
        if (!isset($this->onQueue[$index])) {
            if (!$this->map->isOutside($step)) {
                $this->queue->enqueue($step);
                $this->onQueue[$index] = true;
            }
        }
    }

    private function dequeue(): Vector
    {
        $current = $this->queue->dequeue();
        $index = $current->y * $this->map->maxY + $current->x;
        unset($this->onQueue[$index]);
        return $current;
    }
}