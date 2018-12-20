<?php
function debug(string $line, ...$format)
{
    if (!defined('MUTE_DEBUG')) {
        fwrite(STDERR, sprintf($line, ...$format) . PHP_EOL);
    }
}

function msp(?string $lbl = null, float $ms = 0)
{
    if ($ms < 0.01) {
        debug("%s: %.2f µs", $lbl, $ms * 1000);

    } else {
        debug("%s: %.2fms", $lbl, $ms);
    }
}

function ms(?float $ms = null): float
{
    if ($ms === null) {
        return microtime(true);
    } else {
        return (microtime(true) - $ms) * 1000;
    }
}

function expect($expected, $actual, $message = '') {
    if($expected != $actual) {
        if( empty($message)) {
            $message = 'Assert failed';
        }
        debug('%s; expected %s got %s', $message, $expected, $actual);
    }
}
