<?php
ini_set('memory_limit', '512M');
$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");

function rotate(SplDoublyLinkedList $board, $steps) {
    if($steps > 0) {
        for($i=0;$i<$steps;$i++) {
            $board->unshift($board->pop());
        }
    }else{
        for($i=0;$i>$steps;$i--) {
            $board->push($board->shift());
        }
    }
}

function getHighScore(int $numPlayers, int $numMarbles): int
{
    $playerScores = [];
    $board = new SplDoublyLinkedList();
    $board->push(0);
    for ($marble = 1; $marble <= $numMarbles; $marble++) {
        if ($marble % 23 === 0) {
            rotate($board, -7);
            $playerScores[$marble % $numPlayers] += $board->pop() + $marble;
        } else {
            rotate($board, 2);
            $board->push($marble);
        }
    }
    return max($playerScores);
}

preg_match('/(?<players>\d+) players; last marble is worth (?<marbles>\d+) points/', $data, $matches);
echo "Part 1: ".getHighScore($matches['players'], $matches['marbles']).PHP_EOL;
echo "Part 2: ".getHighScore($matches['players'], $matches['marbles'] * 100).PHP_EOL;