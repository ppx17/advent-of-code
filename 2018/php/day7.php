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

    public function count(): int
    {
        return $this->stepCount;
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
        $mySteps = $this->localStepsCopy();
        $solved = 0;
        $result = '';
        while ($solved < $mySteps->count()) {
            $step = $mySteps->nextAvailableStep();
            $result .= $step;
            $mySteps->finishStep($step);
            $mySteps->removeFromRequirements($step);
            $solved++;
        }
        return $result;
    }

    public function getDuration(int $workerCount): int
    {
        $mySteps = $this->localStepsCopy();
        $workforce = new Workforce($workerCount);
        $time = 0;
        $solved = 0;
        $finishAt = [];
        while ($solved < $mySteps->count() || count($finishAt) > 0) {
            foreach ($finishAt as $step => $atTime) {
                if ($time === $atTime) {
                    unset($finishAt[$step]);
                    $mySteps->removeFromRequirements($step);
                    $solved++;
                }
            }
            while ($workforce->hasWorker($time)) {
                $step = $mySteps->nextAvailableStep();
                if ($step === null) {
                    break;
                }
                $finishAt[$step] = $time + $this->duration($step);
                $workforce->workUntil($finishAt[$step]);
                $mySteps->finishStep($step);
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

class Workforce
{
    private $workers;
    private $workerCount;

    public function __construct($workerCount)
    {
        $this->workers = [];
        $this->workerCount = $workerCount;
    }

    public function hasWorker(int $time): bool
    {
        return $this->availableWorkerIndex($time) !== null;
    }

    public function workUntil(int $time)
    {
        $worker = $this->availableWorkerIndex($time);
        $this->workers[$worker] = $time;
    }

    private function availableWorkerIndex(int $time): ?int
    {
        for ($i = 0; $i < $this->workerCount; $i++) {
            if (!isset($this->workers[$i]) || $this->workers[$i] < $time) {
                return $i;
            }
        }
        return null;
    }
}

$steps = new Steps();

foreach ($rules as $rule) {
    $steps->addRule($rule['target'], $rule['requirement']);
}

$solver = new Solver($steps);

echo "Part 1: " . $solver->getOrder() . PHP_EOL;
echo "Part 2: " . $solver->getDuration(5) . PHP_EOL;

