<?php

class Runner
{
    private $totalRunTime = 0;

    public function runFiles(array $files)
    {
        $this->totalRunTime = 0;

        foreach ($files as $file) {
            $this->runFile($file);
        }

        printf("\nRan %s files in %s ms. Avg %s ms per file.",
            count($files),
            $this->ms($this->totalRunTime),
            $this->ms($this->totalRunTime / count($files)));
    }

    /**
     * @param $file
     */
    private function runFile($file)
    {
        $s = microtime(true);
        $result = $this->runInScope($file);
        $time = microtime(true) - $s;
        $this->totalRunTime += $time;

        printf("File %s: %s in %s ms\n",
            $file, $this->judgeFile($file, $result), $this->ms($time));
    }

    private function runInScope($file): string
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

        return (trim($result) == trim($this->answerForDay($day))) ? '✔ correct answer' : '⨯ wrong answer';
    }

    private function dayFromFilename($filename)
    {
        return substr(basename($filename), 0, -4);
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
        return round($seconds * 1000);
    }
}

(new Runner())->runFiles(glob('day*.php'));
