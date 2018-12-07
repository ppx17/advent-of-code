<?php
$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");

preg_match_all(
    "/Step (?<requirement>[A-Z]) must be finished before step (?<target>[A-Z]) can begin./",
    $data,
    $rules,
    PREG_SET_ORDER);

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

$steps = new Steps();

foreach ($rules as $rule) {
    $steps->addRule($rule['target'], $rule['requirement']);
}

$solver = new Solver($steps);

echo "Part 1: " . $solver->getOrder() . PHP_EOL;
echo "Part 2: " . $solver->getDuration(5) . PHP_EOL;

