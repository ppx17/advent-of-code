Param(
    [switch]$Part2
);

if($Part2) {
    $c = 1;
}

$a=1; #L1
$b=1; #L2
$d=26; # L3

if($c -ne 0) { #L4 L5
    $c = 7; # L6

    do {
        $d++; # L7
        $c--; # L8
    }while($c -ne 0); # L9
}

do {
    $c = $a; # L10
    do {
        $a++; # L11
        $b--; # L12
    }while($b -ne 0); # L13

    $b = $c; # L14
    $d--; # L15
}while($d -ne 0); # L16

# L17
for($c=16; $c -gt 0; $c--) {
    for($d=12; $d -gt 0; $d--) {
        $a++; # L19
    }
}

Write-Output $a;