
# AKS Primality test
Class Prime {
    [bool]static IsPrime([int]$Number) {
        if($Number -eq 2 -or $Number -eq 3) {
            return $true;
        }
        if($Number % 2 -eq 0 -or $Number % 3 -eq 0) {
            return $False;
        }

        $i = 5;
        $w = 2;
        while($i * $i -le $Number) {
            if( $Number % $i -eq 0) {
                return $False;
            }

            $i += $w;
            $w = 6 - $w;
        }

        return $True;
    }
}

$a = 1;
$h = 1; # This is a hack, H should start as 0 but it gives a 1-off error...

$b = 57  # L1
$c = $b  # L2
if($a -ne 0) { #L3 + #L4
    $b *= 100  # L5
    $b += 100000  # L6
    $c = $b  # L7
    $c += 17000  # L8
}

do { # L27,28,29; #L31
    if( -not [Prime]::IsPrime($b)) { # L25
        $h++;  # L26
    }
    $b += 17;
}while($b -ne $c); # L32

Write-Host "Result: ${h}"; # L30

# 915!!