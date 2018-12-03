<?php
$files = glob('day*.php');

function runInScope($file): string {
    ob_start();
    include $file;
    return ob_get_clean();
}
$total = 0;
foreach($files as $file) {
    printf("File %s: ", $file);

    $s = microtime(true);
    $result = runInScope($file);
    $time = microtime(true) - $s;
    $total += $time;
    $day = substr(basename($file), 0, -4);
    if(file_exists('../expected/'.$day.'.txt')) {
        $expected = file_get_contents('../expected/'.$day.'.txt');
        if(trim($result) == trim($expected)) {
            echo '✔ correct answer';
        }else {
            echo '⨯ wrong answer';
        }
    }else{
        echo '? answer unknown';
    }
    echo ' in '.round($time, 3) . " s".PHP_EOL;
}

printf("\nRan %s files in %s sec. Avg %s per file.",
    count($files),
    round($total, 4),
    round($total / count($files), 4));