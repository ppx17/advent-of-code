<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 15:08
 */

namespace Ppx17\Aoc2018\Days\Day7;


class Solver
{
    private $steps;

    public function __construct(Steps $steps)
    {
        $this->steps = $steps;
    }

    public function getOrder(): string
    {
        $steps = $this->localStepsCopy();
        $result = '';
        while ($steps->count() > 0) {
            $step = $steps->nextAvailableStep();
            $result .= $step;
            $steps->finishStep($step);
            $steps->removeFromRequirements($step);
        }
        return $result;
    }

    public function getDuration(int $workerCount): int
    {
        $steps = $this->localStepsCopy();
        $time = 0;
        $workInProgress = [];
        while ($steps->count() > 0 || count($workInProgress) > 0) {
            foreach ($workInProgress as $step => $atTime) {
                if ($time === $atTime) {
                    unset($workInProgress[$step]);
                    $steps->removeFromRequirements($step);
                }
            }
            while (count($workInProgress) < $workerCount && $step = $steps->nextAvailableStep()) {
                $workInProgress[$step] = $time + $this->duration($step);
                $steps->finishStep($step);
            }

            $time++;
        }
        return --$time;
    }

    private function localStepsCopy()
    {
        // Both steps destroy the content of the steps dataset, so they need their personal copy.
        return clone $this->steps;
    }

    private function duration(string $step)
    {
        return ord($step) - 4;
    }
}