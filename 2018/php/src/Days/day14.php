<?php
// TODO: Optimize memory usage
ini_set('memory_limit', '2048M');
$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");
require_once 'helpers.php';

function predictScoresForward(int $numRecipes)
{
    $scoreboard = [];
    $scoreboard[] = 3;
    $scoreboard[] = 7;
    $count = 2;

    $first = 0;
    $second = 1;
    $target = ($numRecipes + 10);

    while ($count < $target) {
        $sum = $scoreboard[$first] + $scoreboard[$second];

        if ($sum < 10) {
            $scoreboard[] = $sum;
            $count++;
        } else {

            $scoreboard[] = floor($sum / 10);
            $count++;
            if ($target - $count > 1) {
                $scoreboard[] = $sum % 10;
                $count++;
            }
        }

        $first = ($first + 1 + $scoreboard[$first]) % $count;
        $second = ($second + 1 + $scoreboard[$second]) % $count;
    }

    return implode('', array_slice($scoreboard, $count - 10, 10));
}

class BackwardsSearch
{
    private $pattern;
    private $scoreboard;
    private $count;
    private $patternLength;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
        $this->patternLength = strlen($pattern);
    }

    public function search()
    {
        $this->scoreboard = [];
        $this->count = 0;
        $this->addScore(3);
        $this->addScore(7);

        $first = 0;
        $second = 1;

        while (true) {
            $sum = $this->scoreboard[$first] + $this->scoreboard[$second];
            if ($sum < 10) {
                $this->addScore($sum);

                if ($this->matchesPattern()) {
                    return $this->count - ($this->patternLength+1);
                }
            } else {
                $this->addScore(floor($sum / 10));

                if ($this->matchesPattern()) {
                    return $this->count - ($this->patternLength+1);
                }

                $this->addScore($sum % 10);

                if ($this->matchesPattern()) {
                    return $this->count - ($this->patternLength+1);
                }
            }
            $first = ($first + 1 + $this->scoreboard[$first]) % $this->count;
            $second = ($second + 1 + $this->scoreboard[$second]) % $this->count;
        }
    }

    private function matchesPattern(): bool
    {
        $count = count($this->scoreboard);
        if ($count < $this->patternLength) {
            return false;
        }
        for ($i = 0; $i < $this->patternLength; $i++) {
            $offset = $count - ($this->patternLength - $i) - 1;
            if ($this->pattern[$i] != $this->scoreboard[$offset]) {
                return false;
            }
        }
        return true;
    }

    private function addScore(int $rating): void
    {
        $this->scoreboard[] = $rating;
        $this->count++;
    }
}

function searchBackwards(string $pattern)
{
    $bs = new BackwardsSearch($pattern);
    return $bs->search();
}

echo "Part 1: " . predictScoresForward(intval($data)) . PHP_EOL;
echo "Part 2: " . searchBackwards($data);
