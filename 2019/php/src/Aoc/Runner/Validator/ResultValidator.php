<?php


namespace Ppx17\Aoc2019\Aoc\Runner\Validator;


use Illuminate\Support\Str;
use Ppx17\Aoc2019\Aoc\Runner\Result;

class ResultValidator
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function validate(Result $result): ValidatedResult
    {
        $validationResult = new ValidatedResult($result);

        $content = $this->getExpectedContent($result->getDay()->dayNumber());
        if (is_null($content)) {
            return $validationResult;
        }

        $parts = $this->parseParts($content);
        if (isset($parts[0]) && $parts[0] !== false) {
            $validationResult->getPart1()->setExpectation($parts[0]);
        }
        if (isset($parts[1]) && $parts[1] !== false) {
            $validationResult->getPart2()->setExpectation($parts[1]);
        }

        return $validationResult;
    }

    private function getExpectedContent(int $day): ?string
    {
        if (!file_exists($this->getExpectedPath($day)) || !is_readable($this->getExpectedPath($day))) {
            return null;
        }
        return file_get_contents($this->getExpectedPath($day));
    }

    private function parseParts(string $content): array
    {
        $parts = [];
        $active = 0;
        collect(explode("\n", $content))
            ->each(function($line) use(&$parts, &$active) {
                if(Str::startsWith($line, 'Part 1: ')) {
                    $active = 0;
                }elseif(Str::startsWith($line,'Part 2: ')) {
                    $active = 1;
                }
                $parts[$active] .= substr($line, 8)."\n";
            });
        return $parts;
    }

    private function getExpectedPath(int $day)
    {
        return $this->path . DIRECTORY_SEPARATOR . 'day' . $day . '.txt';
    }
}
