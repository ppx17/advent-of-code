<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 15:08
 */

namespace Ppx17\Aoc2018\Days\Day7;


class Steps
{
    private $steps;
    private $stepCount;

    public function __construct()
    {
        foreach (range('A', 'Z') as $step) {
            $this->steps[$step] = [];
        }
        $this->stepCount = count($this->steps);
    }

    public function count(): int {
        return count($this->steps);
    }

    public function addRule(string $target, string $requirement): void
    {
        $this->steps[$target][$requirement] = true;
    }

    public function nextAvailableStep(): ?string
    {
        foreach ($this->steps as $step => $requirements) {
            if (count($requirements) === 0) {
                return $step;
            }
        }
        return null;
    }

    public function finishStep($step): void
    {
        unset($this->steps[$step]);
    }

    public function removeFromRequirements($step): void
    {
        foreach ($this->steps as $key => $unused) {
            unset($this->steps[$key][$step]);
        }
    }
}