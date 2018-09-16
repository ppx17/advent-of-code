$a = 1;

$b = 57  # L1
$c = $b  # L2
if($a -ne 0) { #L3 + #L4
    $b *= 100  # L5
    $b -= -100000  # L6
    $c = $b  # L7
    $c -= -17000  # L8
}
do {
    $f = 1  # L9
    $d = 2  # L10
    do {
        $e = 2  # L11
        do {
            if(($d * $e) - $b -eq 0) { #L12,13,14,15
                $f = 0  # L16
            }
            $e++;  # L17
        } while(($e - $b) -ne 0); #L18,19,20
        $d++  # L21
    } while(($d - $b) -ne 0); #L22,23,24
    if($f -eq 0) { # L25
        $h++  # L26
    }
    if(($b - $c) -eq 0) { # L27,28,29
        Write-Host "Result: ${h}"; Exit; # L30
    }
    $b += 17  # L31
}while($true); # L32
