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
            $g = $d  # L12
            $g *= $e  # L13
            $g -= $b  # L14
            if($g -eq 0) { #L15
                $f = 0  # L16
            }
            $e -= -1  # L17
            $g = $e  # L18
            $g -= $b  # L19
        } while($g -ne 0); #L20
        $d -= -1  # L21
        $g = $d  # L22
        $g -= $b  # L23
    } while($g -ne 0); #L24
    if($f -eq 0) { # L25
        $h -= -1  # L26
    }
    $g = $b  # L27
    $g -= $c  # L28
    if($g -eq 0) { # L29
        Write-Host "Result: ${h}"; Exit; # L30
    }
    $b -= -17  # L31
}while($true); # L32
