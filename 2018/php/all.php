<?php

class Runner
{
    private $totalRunTime = 0;

    public function runFiles(array $files): int
    {
        $this->totalRunTime = 0;

        $errors = 0;
        foreach ($files as $file) {
            if (!$this->runFile($file)) {
                echo "Failed on $file\n";
                $errors++;
            }
        }

        printf("\nRan %s files in %s ms. Avg %s ms per file.\n",
            count($files),
            $this->ms($this->totalRunTime),
            $this->ms($this->totalRunTime / count($files)));

        return $errors;
    }

    /**
     * @param $file
     */
    private function runFile($file): bool
    {
        // To prevent disk latency from influencing benchmark results we read the data outside the measurement.
        $data = $this->dataForDay($this->dayFromFilename($file));
        $s = microtime(true);
        $result = $this->runInScope($file, $data);
        $time = microtime(true) - $s;
        $this->totalRunTime += $time;

        printf("File %s: %s in %s ms\n",
            $file, $this->judgeFile($file, $result),
            str_pad($this->ms($time), 7, ' ', STR_PAD_LEFT));

        return $this->correctAnswer($result, $this->dayFromFilename($file));
    }

    private function runInScope(string $file, ?string $data = null): string
    {
        ob_start();
        include $file;
        return ob_get_clean();
    }

    private function judgeFile($file, $result)
    {
        $day = $this->dayFromFilename($file);
        if (!$this->hasAnswerForDay($day)) {
            return '? answer unknown';
        }

        return $this->correctAnswer($result, $day) ? '✔ correct answer' : '⨯ wrong answer';
    }

    private function dayFromFilename($filename)
    {
        return basename($filename, '.php');
    }

    private function hasAnswerForDay($day)
    {
        return file_exists($this->fileForDay($day));
    }

    private function fileForDay($day)
    {
        return '../expected/' . $day . '.txt';
    }

    private function answerForDay($day)
    {
        if ($this->hasAnswerForDay($day)) {
            return file_get_contents($this->fileForDay($day));
        }
        return null;
    }

    private function ms($seconds)
    {
        return round($seconds * 1000, 2);
    }

    private function dataForDay($day): ?string
    {
        return file_exists($this->dataFileForDay($day)) ?
            file_get_contents($this->dataFileForDay($day)) : null;
    }

    /**
     * @param $day
     * @return string
     */
    private function dataFileForDay($day): string
    {
        return "../input/input-" . $day . ".txt";
    }

    /**
     * @param $result
     * @param string $day
     * @return bool
     */
    private function correctAnswer($result, string $day): bool
    {
        if (!$this->hasAnswerForDay($day)) {
            return false;
        }
        return (trim($result) == trim($this->answerForDay($day)));
    }
}

$errorCount = (new Runner())->runFiles(glob('day*.php'));
if ($errorCount > 0) {
    echo $errorCount;
    exit(1);
}
