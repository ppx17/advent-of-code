$a = 1;

$h = 0;

$b = 57  # L1
$c = $b  # L2
if($a -ne 0) { #L3 + #L4
    $b *= 100 # L5
    $b -= -100000  # L6
    $c = $b  # L7
    $c -= -17000  # L8
}

do { # L27,28,29; #L31
    $f = 1  # L9
    for($d=2; ($d -ne $b); $d++) { # 10; #L22,23,24; # L21
        for($e=2; ($e -ne $b); $e++) { # L11; #L18,19,20; # L17
            if(($d * $e) -eq $b) { #L12,13,14,15
                $f = 0  # L16
            }
        }
    }
    if($f -eq 0) { # L25
        $h -= -1  # L26
    }
}while($b -ne $c); # L32

Write-Host "Result: ${h}"; Exit; # L30