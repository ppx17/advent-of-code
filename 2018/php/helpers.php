<?php
function ms(?string $name = null, float $ms = 0) {
    if($name === null) {
        return microtime(true);
    }
    printf("%s: %s ms\n", $name, round((microtime(true) - $ms) * 1000, 2));
}