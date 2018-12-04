<?php
$files = glob('day*.php');

function runInScope($file): string {
    ob_start();
    include $file;
    return ob_get_clean();
}
$total = 0;

function ms($seconds) {
    return round($seconds * 1000);
}

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
    echo ' in '.ms($time) . " ms".PHP_EOL;
}

printf("\nRan %s files in %s ms. Avg %s ms per file.",
    count($files),
    ms($total),
    ms($total / count($files)));